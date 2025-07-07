<?php

namespace App\Controller;

use App\Core\Auth;
use App\Core\Database;
use App\Model\Role;
use App\Model\User;
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
        if (isset($_GET['msg'])) {
            switch ($_GET['msg']) {
                case "password_changed":
                    echo "<script>alert('Hasło zostało pomyślnie zmienione. Zaloguj się ponownie.');</script>";
                    break;
                case "inactive":
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

        require __DIR__ . '/../View/loginPanel.php';
    }

    public function login(): void
    {
        Auth::startSessionIfItsNot();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->attemptLogin($_POST['login'], $_POST['password']);

            if ($user) {
                if (!$user->confirmed) {
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
        require __DIR__ . '/../View/registerForm.php';
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
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $token = bin2hex(random_bytes(32));
                    $createdAt = date('Y-m-d H:i:s');
                    $link = "http://localhost:8000/activate?token=$token";
                    $message = "Kliknij w link aby aktywowac konto: \n\n$link";

                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $this->conn->prepare("INSERT INTO users (login, email, password, role_id, activation_token, activation_token_created_at, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?)");

                    $stmt->execute([$login, $email, $passwordHash, 1, $token, $createdAt, 0]);

                    mail($email, "Aktywacja konta Garden", $message, "From: no-reply@garden.pl");
                    header('Location:/login?msg=user_added');
                } catch (Exception $e) {
                    if ($e->getCode() == "23000") {
                        echo"<script>alert('Użytkownik o podanym loginie lub emailu już istnieje.');</script><script>window.location='/register'</script>)";
                    } else {
                        echo $e->getMessage();
                    }
                    $conn = null;
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

    #[NoReturn] public function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function showChangePasswordForm(): void
    {
        require __DIR__ . '/../View/changePasswordForm.php';
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

        try {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || password_verify($oldPassword, $user['password'])) {
                die("Nieprawidłowe stare hasło.");
            }

            $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newPasswordHash, $userId]);

            session_unset();
            session_destroy();

            header("Location: /login?msg=password_changed");
            exit;
        } catch (PDOException $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }

    private function attemptLogin(string $login, string $password): ?User
    {
        $query = "
            SELECT
                u.id AS user_id,
                u.login,
                u.email,
                u.password,
                u.confirmed,
                r.id AS role_id,
                r.name AS role_name,
                p.name AS permission_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            LEFT JOIN role_permission rp ON rp.role_id = r.id
            LEFT JOIN permissions p ON p.id = rp.permission_id
            WHERE u.login = ?
        ";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$login]);
            $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($response == null) {
            return null;
        }

        $user = $response[0];

        if (!$user['confirmed']) {
            header("Location: /login?msg=activation");
            exit;
        }

        if (!password_verify($password, $user['password'])) {
            header("Location: /login?msg=wrong_password");
            exit;
        }

        $permissions = [];
        foreach ($response as $row) {
            if ($row['permission_name']) {
                $permissions[] = $row['permission_name'];
            }
        }

        $role = new Role([
            'id' => $user['role_id'],
            'name' => $user['role_name'],
            'permissions' => $permissions
        ]);

        return new User([
            'id' => $user['user_id'],
            'login' => $user['login'],
            'email' => $user['email'],
            'confirmed' => $user['confirmed'],
            'role' => $role
        ]);
    }
}
