<?php

namespace courseProject\src\Commands\Users;

use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUser extends Command
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
            ->setName('users:update')
            ->setDescription('Update user data')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a user to update'
            )
            ->addOption(
                'user-name',
                'f',
                InputOption::VALUE_OPTIONAL,
                'First name'
            )
            ->addOption(
                'user-surname',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Last name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userName = $input->getOption('user-name');
        $userSurname = $input->getOption('user-surname');

        if(empty($userName) && empty($userSurname)) {
            $output->writeln('Nothing to update');
            return Command::SUCCESS;
        }

        $uuid = new UUID($input->getArgument('uuid'));
        $user = $this->usersRepository->get($uuid);

        $updatedUser = new Users(
            uuid: $uuid,
            userLogin: $user->getUserLogin(),
            userName: empty($userName) ? $user->getUserName(): $userName,
            userSurname: empty($userSurname) ? $user->getUserSurname() : $userSurname,
            hashedPassword: $user->getHashedPassword()
        );

        $this->usersRepository->save($updatedUser);
        $output->writeln("User updater: $uuid");
        return Command::SUCCESS;
    }
}