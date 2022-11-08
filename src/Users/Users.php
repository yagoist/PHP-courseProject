<?php

namespace src\Users;

use src\UUID;

class Users
{
    public function __construct(
        private UUID $uuid,
        private string $userLogin,
        private string $userName,
        private string $userSurname
    )
    {
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