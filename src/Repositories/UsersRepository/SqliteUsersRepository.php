<?php

namespace courseProject\src\Repositories\UsersRepository;

use PDO;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use PDOStatement;
use courseProject\src\Exceptions\AppException;
use Psr\Log\LoggerInterface;

class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    )
    {

    }
    public function save(Users $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, login, user_name, user_surname, password)
                    VALUES (:uuid, :login, :user_name, :user_surname, :password)
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
            ':password' => $user->getHashedPassword()

        ]);

        $this->logger->info("User recorded in SQL: {$user->getUuid()}");
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
            $result['user_surname'],
            $result['password']
        );

        $this->logger->warning("User get from SQL: $uuid");
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByUserLogin(string $userLogin): Users
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE login = ?'
        );
        $statement->execute([
            $userLogin
        ]);

        return $this->getUser($statement, $userLogin);
    }

    /**
     * @throws UserNotFoundException
     */
    private function getUser(PDOStatement $statement, string $userLogin): Users
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false)
        {
            throw new UserNotFoundException(
                "Cannot find user by login: $userLogin"
            );
        }

        return new Users(
            new UUID($result['uuid']),
            $result['login'],
            $result['user_name'],
            $result['user_surname'],
            $result['password']
        );
    }
}