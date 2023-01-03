<?php

namespace courseProject\src\Users;

use courseProject\src\UUID;

class Users
{
    public function __construct(
        private UUID $uuid,
        private string $userLogin,
        private string $userName,
        private string $userSurname,
        private string $hashedPassword
    )
    {
    }

    /**
     * @return string
     */
    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    /**
     * @param string $password
     * @return string
     */
    private static function hashPassword(string $password, UUID $uuid): string
    {
        return hash('sha256', $uuid . $password);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hashPassword($password, $this->uuid);
    }

    /**
     * @param string $login
     * @param string $name
     * @param string $surname
     * @param string $password
     * @return static
     */
    public static function createFrom(
        string $login,
        string $name,
        string $surname,
        string $password
    ): self
    {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $login,
            $name,
            $surname,
            self::hashPassword($password, $uuid)
        );
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getUserLogin(): string
    {
        return $this->userLogin;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getUserSurname(): string
    {
        return $this->userSurname;
    }

    public function __toString(): string
    {
        return $this->userName.' '.$this->userSurname;
    }

}