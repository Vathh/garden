<?php

namespace App\Controller;

use App\Core\Database;
use DateTime;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use PDO;
use PDOException;

class AuthController
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function showLoginForm(): void
    {

        if (isset($_GET['msg']) && $_GET['msg'] == "password_changed") {
            echo "<script>alert('Hasło zostało pomyślnie zmienione. Zaloguj się ponownie.');</script>";
        }

        if (isset($_GET['msg']) && $_GET['msg'] == "inactive") {
            echo "<script>alert('Sesja wygasła. Zaloguj się ponownie.');</script>";
        }

        require __DIR__ . '/../View/loginPanel.php';
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST["login"];
            $password = $_POST["password"];

            $stmt = $this->conn->prepare("SELECT * FROM users WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();

            if ($user && $password == $user['password']) {
                if (!$user['confirmed']) {
                    echo "Konto nieaktywne. Sprawdź pocztę.";
                    exit;
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['last_activity'] = time();

                header("Location: / ");
                exit;
            } else {
                echo "Nieprawidłowy login lub hasło.";
            }
        }
    }

    public function register(): void
    {
        if (isset($_POST['register'])) {
            $login = $_POST['login'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $passwordRepeat = $_POST['passwordRepeat'];
            if (
                $login != "" &&
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
                try {
                    $token = bin2hex(random_bytes(32));
                    $createdAt = date('Y-m-d H:i:s');
                    $link = "http://localhost:8000/activate?token=$token";
                    $message = "Kliknij w link aby aktywowac konto: \n\n$link";
                    mail($email, "Aktywacja konta Garden", $message, "From: no-reply@garden.pl");

                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $this->conn->prepare("INSERT INTO users (login, email, password, activation_token, activation_token_created_at, confirmed) VALUES (?, ?, ?, ?, ?, ?)");

                    $stmt->execute([$login, $email, $password, $token, $createdAt, 0]);
                } catch (PDOException $e) {
                    if ($e->getCode() == "23000") {
                        echo"<script>alert('Użytkownik o podanym loginie lub emailu już istnieje.');</script><script>window.location='/register'</script>)";
                    } else {
                        echo $e->getMessage();
                    }
                    $conn = null;
                    header('Location:/login?msg=user_added');
                }
            } else {
                echo"<script>alert('Coś poszło nie tak, spróbuj ponownie');</script>)<script>window.location='/register'</script>";
            }
        }
    }

    /**
     * @throws Exception
     */
    public function activate(): void
    {
        $token = $_GET['token'] ?? '';

        if ($token) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE activation_token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch();

            if ($user && !$user['confirmed']) {
                $createdAt = new DateTime($user['activation_token_created_at']);
                $now = new DateTime();
                $interval = $createdAt->diff($now);

                if ($interval->days > 15) {
                    $stmt = $this->conn->prepare("DELETE FROM users WHERE activation_token = ?");
                    $stmt->execute([$token]);
                    die("Link do aktywacji wygasł.");
                }

                $stmt = $this->conn->prepare("UPDATE users SET confirmed = 1, activation_token = null WHERE activation_token = ?");
                $stmt->execute([$token]);
                echo "Konto zostało aktywowane.";
            } else {
                echo "Nieprawidłowy token lub konto zostało juz aktywowane.";
            }
        } else {
            echo "Brak tokena aktywacyjnego.";
        }
    }

    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function showChangePasswordForm(): void
    {
        session_start();

        require __DIR__ . '/../View/changePasswordForm.php';
    }

    public function changePassword(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
            exit;
        }

        if (!(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] < 600))) {
            session_unset();
            session_destroy();
            header("Location: /login?msg=inactive");
            exit;
        }

        $_SESSION['last_activity'] = time();

        $userId = $_SESSION['user_id'];
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            die("Nowe hasła różnią się.");
        }

        try {
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || $oldPassword !== $user['password']) {
                die("Nieprawidłowe stare hasło.");
            }

            $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newPassword, $userId]);

            session_unset();
            session_destroy();

            header("Location: /login?msg=password_changed");
            exit;
        } catch (PDOException $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    function userHasPermission($permission): bool{
        $userId = $_SESSION['user_id'];


    }
}
