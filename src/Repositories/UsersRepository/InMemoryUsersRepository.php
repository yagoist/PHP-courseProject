<?php

namespace src\Repositories\UsersRepository;

use src\Exceptions\UserNotFoundException;
use src\Users\Users;
use src\UUID;

class InMemoryUsersRepository implements UsersRepositoryInterface
{
    private array $users = [];

    public function save(Users $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @param int $id
     * @return Users
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Users
    {
        foreach ($this->users as $user)
        {
            if((string)$user->getUuid() === (string)$uuid)
            {
                return $user;
            }
        }
        throw new UserNotFoundException("User not found: $uuid");
    }

}