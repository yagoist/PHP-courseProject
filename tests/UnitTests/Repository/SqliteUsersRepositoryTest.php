<?php

namespace UnitTests\Repository;

use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Repositories\UsersRepository\SqliteUsersRepository;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use courseProject\tests\UnitTests\DummyLogger;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{

    public function testItTrowsAnExceptionWhenUserNotFound(): void
    {
        $connectionMock = $this->createMock(PDO::class);

        $statementStab = $this->createStub(PDOStatement::class);
        $statementStab->method('fetch')->willReturn(false);
        $connectionMock->method('prepare')->willReturn($statementStab);

        $repository = new SqliteUsersRepository($connectionMock, new DummyLogger());
        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot find user by login: batman');

        $repository->getByUserLogin('batman');
    }

    public function testItSavesUserToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock =$this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '746104f5-8d05-4209-821b-90826aeb63ef',
                ':login' => 'batman',
                ':user_name' => 'Ivan',
                ':user_surname' => 'Kozyulin',
                ':password' => 'some_password'
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());
        $repository->save(
            new Users(
                new UUID('746104f5-8d05-4209-821b-90826aeb63ef'),
                'batman',
                'Ivan',
                'Kozyulin',
                'some_password'
            )
        );
    }



}