<?php

namespace UnitTests\Actions;

use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Http\Actions\Users\FindByUserLogin;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use PHPUnit\Framework\TestCase;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;

class FindByUserLoginActionTest extends TestCase
{

    /**
     * @runTestInSeparateProcess
     * @preserveGlobalState disabled
     * @throws /JsonException
     */
    public function testItReturnsErrorResponseIfNoUserLoginProvided(): void
    {
        $request = new Request([], [], '');
        $usersRepository = $this->usersRepository([]);
        $action = new FindByUserLogin($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: login"}');
        $response->send();
    }

    /**
     * @runTestInSeparateProcess
     * @preserveGlobalState disabled
     * @throws /JsonException
     */
    public function testIReturnsErrorResponseIfUserNotFound(): void
    {
        $request = new Request(['login' => 'batman'], [], '');
        $usersRepository = $this->usersRepository([]);
        $action = new FindByUserLogin($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(ErrorResponse::class, $response);
        $this->expectOutputString('{"success":false,"reason":"Not found"}');
        $response->send();
    }

    /**
     * @runTestInSeparateProcess
     * @preserveGlobalState disabled
     * @throws /JsonException
     */
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['login' => 'batman'], [], '');

        $usersRepository = $this->usersRepository([
            new Users(
                UUID::random(),
                'batman',
                'Ivan',
                'Nikitin'
            ),
        ]);

        $action = new FindByUserLogin($usersRepository);
        $response = $action->handle($request);
        $this->assertInstanceOf(SuccessfulResponse::class, $response);
        $this->expectOutputString('{"success":true,"data":{"login":"batman","name":"Ivan Nikitin"}}');
        $response->send();
    }

    private function usersRepository(array $users): UsersRepositoryInterface
    {
        return new class($users) implements UsersRepositoryInterface {
            public function __construct(
                private array $users
            )
            {
            }

            public function save(Users $user): void
            {
                // TODO: Implement save() method.
            }

            public function get(UUID $uuid): Users
            {
                throw new UserNotFoundException('Not found');
            }

            public function getByUserLogin(string $userLogin): Users
            {
                foreach ($this->users as $user) {
                    if ($user instanceof Users && $userLogin === $user->getUserLogin())
                    {
                        return $user;
                    }
                }
                throw new UserNotFoundException('Not found');
            }
        };
    }
}