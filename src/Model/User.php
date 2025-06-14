<?php

namespace App\Model;

class User
{
    public int $id;
    public string $login;
    public string $email;
    public Role $role;
    public bool $confirmed;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->login = $data['login'];
        $this->email = $data['email'];
        $this->role = $data['role'];
        $this->confirmed = $data['confirmed'];
    }

    public function hasPermission($permission): bool
    {
        return $this->role->hasPermission($permission);
    }
}
