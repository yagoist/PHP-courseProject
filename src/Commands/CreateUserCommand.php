<?php

namespace courseProject\src\Commands;

use courseProject\src\Exceptions\ArgumentException;
use courseProject\src\Exceptions\CommandException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use Psr\Log\LoggerInterface;


class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    )
    {
    }

    /**
     * @throws CommandException
     * @throws ArgumentException
     */
    public function handle(Arguments $arguments): void
    {
//        $input = $this->parseRawInput($rawInput);
        $this->logger->info("Create user comand started");
        $userLogin = $arguments->get('login');

        if ($this->userExist($userLogin))
        {
            $this->logger->warning("User already exist: $userLogin");
            return;
        }

        $uuid = UUID::random();

        $this->usersRepository->save(
            new Users(
                $uuid,
                $userLogin,
                $arguments->get('user_name'),
                $arguments->get('user_surname')
            )
        );
        $this->logger->info("User created: $uuid");
    }

//    /**
//     * @throws CommandException
//     */
//    private function parseRawInput(array $rawInput): array
//    {
//        $input = [];
//
//        foreach ($rawInput as $argument)
//        {
//            $parts = explode('=', $argument);
//            if(count($parts) !== 2)
//            {
//                continue;
//            }
//            $input[$parts[0]] = $parts[1];
//        }
//
//        foreach (['login', 'user_name', 'user_surname'] as $argument)
//        {
//            if (!array_key_exists($argument, $input))
//            {
//                throw new CommandException(
//                    "No required argument provided: $argument"
//                );
//            }
//
//            if(empty($input[$argument]))
//            {
//                throw  new CommandException(
//                    "Empty argument provided: $argument"
//                );
//            }
//            return $input;
//        }
//    }

    private function userExist(string $userLogin): bool
    {
        try {
            $this->usersRepository->getByUserLogin($userLogin);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }

}