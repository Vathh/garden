<?php

namespace App\Model;

class User
{
    private int $id;
    private string $login;
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
}
