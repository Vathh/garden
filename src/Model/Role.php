<?php

namespace App\Model;

class Role
{
    private int $id;
    private string $name;
    private array $permissions = [];

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions);
    }

//      SETTERS
    public function setId(int $id): Role
    {
        $this->id = $id;
        return $this;
    }
    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }
    public function setPermissions(array $permissions): Role
    {
        $this->permissions = $permissions;
        return $this;
    }

//      GETTERS
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
