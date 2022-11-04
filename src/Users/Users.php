<?php

namespace src\Users;

class Users
{
    public function __construct(
        private int $id,
        private string $userName,
        private string $userSurname
    )
    {

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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