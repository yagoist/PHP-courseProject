<?php

namespace courseProject\src\Http\Actions\Users;

use courseProject\src\Exceptions\HttpException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Http\Actions\ActionInterface;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\Response;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;

class FindByUserLogin implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $userLogin = $request->query('login');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->getByUserLogin($userLogin);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        return new SuccessfulResponse([
           'login' => $user->getUserLogin(),
           'name' => $user->getUserName().' '.$user->getUserSurname()
        ]);
    }
}