<?php
declare(strict_types=1);

class User
{
    private $id;
    private $login;
    private $email;
    private $password;
    private $name;
    private $roles;

    public function __construct(
        string $login,
        string $email,
        string $password,
        string $name,
        array $roles
    ) {
        $this->login = $login;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->name = $name;
        $this->roles = $roles;
    }

    public function getId()
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
