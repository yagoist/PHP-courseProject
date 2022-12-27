<?php

namespace courseProject\src\Repositories\UsersRepository;


use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Users\Users;
use courseProject\src\UUID;


class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];

    public function save(Users $user): void
    {
        $this->users[] = $user;
    }


    /**
     * @param UUID $uuid
     * @return Users
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Users
    {
        foreach ($this->users as $user)
        {
            if($user->id() === $uuid)
            {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $uuid");
    }

    public function getByUserLogin(string $userLogin): Users
    {

    }
}