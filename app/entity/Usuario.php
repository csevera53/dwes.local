<?php

namespace dwes\app\entity;
use dwes\app\entity\IEntity;

class Usuario implements IEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $role;

    /**
     * @param string $username
     * @param string $password
     * @param string $role
     */
    public function __construct(string $username = "", string $password = "", string $role = "")
    {
        $this->id = null;
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string $username
     * @return Usuario
     */
    public function setUsername(string $username): Usuario
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return Usuario
     */
    public function setPassword(string $password): Usuario
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $role
     * @return Usuario
     */
    public function setRole(string $role): Usuario
    {
        $this->role = $role;
        return $this;
    }
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'role' => $this->getRole()
        ];
    }
    public function __toString(): string
    {
        return $this->getUsername();
    }
} 