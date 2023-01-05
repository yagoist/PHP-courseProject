<?php

namespace courseProject\src\Commands\Users;

use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('users:create')
            ->setDescription('Create new user')
            ->addArgument('login', InputArgument::REQUIRED, 'User login')
            ->addArgument('user_name', InputArgument::REQUIRED, 'User first name')
            ->addArgument('user_surname', InputArgument::REQUIRED, 'User second name')
            ->addArgument('password', InputArgument::REQUIRED, 'User password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Create user command started');
        $login = $input->getArgument('login');

        if ($this->userExists($login)) {
            $output->writeln("User already exists: $login");
            return Command::FAILURE;
        }

        $user = Users::createFrom(
            $login,
            $input->getArgument('user_name'),
            $input->getArgument('user_surname'),
            $input->getArgument('password')
        );
        $this->usersRepository->save($user);

        $output->writeln('User created: '.$user->getUuid());
        return Command::SUCCESS;
    }

    private function userExists(string $login): bool
    {
        try {
            $this->usersRepository->getByUserLogin($login);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}