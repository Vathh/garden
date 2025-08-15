<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use App\Model\User;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use Random\RandomException;

class AuthController
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function showLoginForm(): void
    {
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case "password_changed":
                    echo "<script>alert('Hasło zostało pomyślnie zmienione. Zaloguj się ponownie.');</script>";
                    break;
                case "inactive":
                    session_unset();
                    session_destroy();
                    echo "<script>alert('Sesja wygasła. Zaloguj się ponownie.');</script>";
                    break;
                case "activation":
                    echo "<script>alert('Konto nieaktywne. Sprawdź pocztę.');</script>";
                    break;
                case "user_added":
                    echo "<script>alert('Pomyślnie dodano użytkownika. Potwierdź rejestrację klikając w link aktywacyjny wysłany na podany adres email.');</script>";
                    break;
                case "wrong_password":
                    echo "<script>alert('Nieprawidłowy login lub hasło. Spróbuj ponownie.');</script>";
                    break;
            }
        }

        try {
            View::render('pages.loginPanel');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function login(): void
    {
        Auth::startSessionIfItsNot();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->attemptLogin($_POST['login'], $_POST['password']);

            if ($user) {
                if (!$user->isConfirmed()) {
                    header("Location: /login?msg=activation");
                    exit;
                }
                $_SESSION['user'] = $user;
                $_SESSION['last_activity'] = time();
                header("Location: / ");
                exit;
            } else {
                header("Location: /login?msg=wrong_password");
                exit;
            }
        }
    }

    public function showRegisterForm(): void
    {
        try {
            View::render('pages.registerForm');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    /**
     * @throws RandomException
     */
    public function register(): void
    {
        if (isset($_POST['register'])) {
            $login = $_POST['login'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordRepeat = $_POST['passwordRepeat'];
            if ($login != "" &&
                strlen($login) >= 5 &&
                preg_match('/^[a-zA-Z0-9]+$/', $login) &&
                $email != "" &&
                filter_var($email, FILTER_VALIDATE_EMAIL) &&
                $password != "" &&
                $passwordRepeat != "" &&
                $password === $passwordRepeat &&
                strlen($password) >= 5 &&
                preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)
            ) {
                $token = bin2hex(random_bytes(32));
                $link = "http://localhost:8000/activate?token=$token";
                $message = "Kliknij w link aby aktywowac konto: \n\n$link";
                if (User::add($login, $email, $password, $token)) {
                    mail($email, "Aktywacja konta Garden", $message, "From: no-reply@garden.pl");
                    header('Location:/login?msg=user_added');
                } else {
                    echo"<script>alert('Coś poszło nie tak, spróbuj ponownie');</script>)<script>window.location='/register'</script>";
                }
            } else {
                echo"<script>alert('Coś poszło nie tak, spróbuj ponownie');</script>)<script>window.location='/register'</script>";
            }
        }
    }

    public function activate(): void
    {
        $token = $_GET['token'] ?? '';

        User::activate($token);
    }

    #[NoReturn] public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function showChangePasswordForm(): void
    {
        try {
            View::render('pages.changePasswordForm');
        } catch (Exception $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    public function changePassword(): void
    {
        Auth::startSessionIfItsNot();

        Auth::requireAuth();

        $userId = unserialize($_SESSION['user'])->id;
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            die("Nowe hasła różnią się.");
        }

        User::changePassword($userId, $oldPassword, $newPassword);
    }

    private function attemptLogin(string $login, string $password): ?User
    {
        $user = User::findByLogin($login);

        if ($user == null) {
            return null;
        }

        if (!$user['confirmed']) {
            header("Location: /login?msg=activation");
            exit;
        }

        if (!password_verify($password, $user->getPasswordHash())) {
            header("Location: /login?msg=wrong_password");
            exit;
        }

        return $user;
    }
}
