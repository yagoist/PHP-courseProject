<?php

namespace courseProject\src\Http\Actions\Users;

use Couchbase\User;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Http\Actions\ActionInterface;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\Response;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;
use courseProject\src\UUID;

class CreateUser implements ActionInterface
{

    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $newUserUuid = UUID::random();

            $user = new Users(
                $newUserUuid,
                $request->jsonBodyField('login'),
                $request->jsonBodyField('user_name'),
                $request->jsonBodyField('user_surname'),
                $request->jsonBodyField('password')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->usersRepository->save($user);

        return new SuccessfulResponse([
            'uuid' => (string)$newUserUuid
        ]);
    }
}