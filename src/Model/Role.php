<?php

namespace App\Model;

class Role
{
    public int $id;
    public string $name;
    public array $permissions = [];

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->permissions = $data['permissions'];
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }
}
