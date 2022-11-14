<?php

namespace courseProject\src\Repositories\UsersRepository;

use PDO;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use src\Exceptions\AppException;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {

    }
    public function save(Users $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, login, user_name, user_surname)
                    VALUES (:uuid, :login, :user_name, :user_surname)
                    ON CONFLICT (uuid) DO UPDATE SET
                    login = :login,
                    user_name = :user_name,
                    user_surname = :user_surname'
        );
        $statement->execute([
            ':uuid' => (string)$user->getUuid(),
            ':login' => $user->getUserLogin(),
            ':user_name' => $user->getUserName(),
            ':user_surname' => $user->getUserSurname(),

        ]);
    }

    /**
     * @throws AppException
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): Users
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        $statement->execute([
            $uuid
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException(
                "cannot get user: $uuid"
            );
        }

        return new Users(
            new UUID($result['uuid']),
            $result['login'],
            $result['user_name'],
            $result['user_surname']
        );

    }
}