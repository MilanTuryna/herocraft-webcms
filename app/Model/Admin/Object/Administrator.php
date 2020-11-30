<?php

namespace App\Model\Admin\Object;

/**
 * Class Administrator
 *
 * Class represent user in administration
 */
class Administrator
{
    private string $name;
    private string $email;
    private int $id;
    private array $permissions;

    public function __construct(string $name, string $email, int $id, array $permissions)
    {
        $this->name = $name;
        $this->email = $email;
        $this->id = $id;
        $this->permissions = $permissions;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function __toString(): string {
        return $this->name;
    }
}