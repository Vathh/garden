<?php

namespace App\Model;

use App\Core\Database;
use DateMalformedStringException;
use DateTime;
use Exception;
use PDO;
use PDOException;

class User
{
    private int $id;
    private string $login;
    private string $passwordHash;
    private string $email;


    private Role $role;
    private bool $confirmed;

    public function hasPermission($permission): bool
    {
        return $this->role->hasPermission($permission);
    }

//    SETTERS
    public function setId(int $id): User
    {
        $this->id = $id;
        return $this;
    }
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }
    public function setRole(Role $role): User
    {
        $this->role = $role;
        return $this;
    }
    public function setConfirmed(bool $confirmed): User
    {
        $this->confirmed = $confirmed;
        return $this;
    }

//    GETTERS
    public function getId(): int
    {
        return $this->id;
    }
    public function getLogin(): string
    {
        return $this->login;
    }
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getRole(): Role
    {
        return $this->role;
    }
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public static function findByLogin(string $login): ?User
    {
        $conn = Database::getInstance()->getConnection();

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
        $stmt = $conn->prepare($query);
        $stmt->execute([$login]);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($response == null) {
            return null;
        }

        $user = $response[0];

        $permissions = [];
        foreach ($response as $row) {
            if ($row['permission_name']) {
                $permissions[] = $row['permission_name'];
            }
        }

        $role = (new Role())
            ->setId($user['role_id'])
            ->setName($user['role_name'])
            ->setPermissions($permissions);

        return (new User())
            ->setId($user['user_id'])
            ->setLogin($user['login'])
            ->setEmail($user['email'])
            ->setConfirmed($user['confirmed'])
            ->setRole($role);
    }

    public static function add(string $login, string $email, string $password, string $token): bool
    {
        $conn = Database::getInstance()->getConnection();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $createdAt = date('Y-m-d H:i:s');

        try {
            $stmt = $conn->prepare("INSERT INTO users (login, email, password, role_id, activation_token, activation_token_created_at, confirmed) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $succeeded = $stmt->execute([$login, $email, $passwordHash, 1, $token, $createdAt, 0]);
        } catch (Exception $e) {
            if ($e->getCode() == "23000") {
                echo"<script>alert('Użytkownik o podanym loginie lub emailu już istnieje.');</script><script>window.location='/register'</script>)";
            } else {
                echo $e->getMessage();
            }
            $conn = null;
        }

        return $succeeded;
    }

    public static function activate(string $token): void
    {
        $conn = Database::getInstance()->getConnection();

        if ($token) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE activation_token = ?");
            $stmt->execute([$token]);
            $user = $stmt->fetch();

            if ($user && !$user['confirmed']) {
                try {
                    $createdAt = new DateTime($user['activation_token_created_at']);
                } catch (DateMalformedStringException $e) {
                    echo "Błąd: " . $e->getMessage();
                }
                $now = new DateTime();
                $interval = $createdAt->diff($now);

                if ($interval->days > 15) {
                    $stmt = $conn->prepare("DELETE FROM users WHERE activation_token = ?");
                    $stmt->execute([$token]);
                    die("Link do aktywacji wygasł.");
                }

                $stmt = $conn->prepare("UPDATE users SET confirmed = 1, activation_token = null WHERE activation_token = ?");
                $stmt->execute([$token]);
                echo "Konto zostało aktywowane.";
            } else {
                echo "Nieprawidłowy token lub konto zostało juz aktywowane.";
            }
        } else {
            echo "Brak tokena aktywacyjnego.";
        }
    }

    public static function changePassword(string $userId, string $oldPassword, string $newPassword): void
    {
        $conn = Database::getInstance()->getConnection();

        try {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || password_verify($oldPassword, $user['password'])) {
                die("Nieprawidłowe stare hasło.");
            }

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newPasswordHash, $userId]);

            session_unset();
            session_destroy();

            header("Location: /login?msg=password_changed");
            exit;
        } catch (PDOException $e) {
            echo "Błąd: " . $e->getMessage();
        }
    }
}
