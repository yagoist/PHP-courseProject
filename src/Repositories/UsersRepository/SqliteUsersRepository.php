<?php

namespace src\Repositories\UsersRepository;

use src\UUID;
use src\Users\Users;
use PDO;

class SqliteUsersRepository
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
                    VALUES (:uuid, :login, :user_name, :user_surname)'
        );
        $statement->execute([
            ':uuid' => (string)$user->getUuid(),
            ':login' => $user->getUserLogin(),
            ':user_name' => $user->getUserName(),
            ':user_surname' => $user->getUserSurname(),

        ]);
    }
    public function get(UUID $uuid): Users
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = ?'
        );
        $statement->execute([
            ':uuid' => (string)$uuid
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException(
                "cannot get user: $uuid"
            );
        }

        return new Users(
            new UUID($result['uuid']),
            new Users($result['user_name'], $result['user_surname'])
        );
    }
}