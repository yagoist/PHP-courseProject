<?php

namespace courseProject\src\Http\Actions\Auth;

use courseProject\src\Exceptions\HttpException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Http\Request;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;

class JsonBodyLoginIdentification implements IdentificationInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    public function user(Request $request): Users
    {
        try {
            $userLogin = $request->jsonBodyField('login');
        } catch (HttpException $e) {
            throw new AuthException($e->getMessage());
        }

        try {
            return $this->usersRepository->getByUserLogin($userLogin);
        } catch (UserNotFoundException $e) {
            throw new AuthException($e->getMessage());
        }
    }
}