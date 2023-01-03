<?php

namespace courseProject\src\Repositories\UsersRepository;


use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Users\Users;
use courseProject\src\UUID;

class DummyUsersRepository implements UsersRepositoryInterface
{

    public function save(Users $user): void
    {
        // TODO: Implement save() method.
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Users
    {
        throw new UserNotFoundException('Not found');
    }

    public function getByUserLogin(string $userLogin): Users
    {
        return new Users(UUID::random(), 'batman', 'firstname', 'surname', 'some_password');
    }
}