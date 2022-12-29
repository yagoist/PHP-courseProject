<?php

namespace courseProject\tests\UnitTests\Commands;

use courseProject\src\Commands\Arguments;
use courseProject\src\Commands\CreateUserCommand;
use courseProject\src\Exceptions\ArgumentException;
use courseProject\src\Exceptions\CommandException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Repositories\UsersRepository\DummyUsersRepository;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use courseProject\tests\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{

    public function testItThrowAnExceptionWhenUserAlreadyExists(): void
    {
        $command = new CreateUserCommand(
            new DummyUsersRepository(), new DummyLogger()
        );

        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('User already exist: Alexey');

        $command->handle(new Arguments(['login' => 'Alexey']));
    }

    public function testItRequiresFirstName(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface {

            public function save(Users $user): void
            {
                // TODO: Implement save() method.
            }

            public function get(UUID $uuid): Users
            {
                throw new UserNotFoundException('not found');
            }

            public function getByUserLogin(string $userLogin): Users
            {
                throw new UserNotFoundException('not found');
            }
        };

        $command = new CreateUserCommand($usersRepository, new DummyLogger());
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('No such argument: user_name');

        $command->handle(new Arguments(['login' => 'batman']));
    }

    private function makeUsersRepository(): UsersRepositoryInterface
    {
        return new class implements UsersRepositoryInterface {
            public function save(Users $user): void
            {
                // TODO: Implement save() method.
            }

            public function get(UUID $uuid): Users
            {
                throw new UserNotFoundException('not found');
            }

            public function getByUserLogin(string $userLogin): Users
            {
                throw new UserNotFoundException('not found');
            }
        };
    }

    public function testItRequiresUserSurname(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('No such argument: user_surname');

        $command->handle(new Arguments([
            'login' => 'batman',
            'user_name' => 'nikolay'
        ]));
    }

    public function testItRequiresUserName(): void
    {
        $command = new CreateUserCommand($this->makeUsersRepository(), new DummyLogger());
        $this->expectException(ArgumentException::class);
        $this->expectExceptionMessage('No such argument: user_name');

        $command->handle(new Arguments(['login' => 'batman']));
    }

    public function testItSavesUserToRepository(): void
    {
        $usersRepository = new class implements UsersRepositoryInterface
        {
            private bool $called = false;

            public function save(Users $user): void
            {
                $this->called = true;
            }

            public function get(UUID $uuid): Users
            {
                throw new UserNotFoundException('not found');
            }

            public function getByUserLogin(string $userLogin): Users
            {
                throw new UserNotFoundException('not found');
            }

            public function wasCalled(): bool
            {
                return $this->called;
            }
        };

        $command = new CreateUserCommand($usersRepository, new DummyLogger());
        $command->handle(new Arguments([
            'login' => 'batman',
            'user_name' => 'simon',
            'user_surname' => 'gvozdev'
        ]));

        $this->assertTrue($usersRepository->wasCalled());
    }
}