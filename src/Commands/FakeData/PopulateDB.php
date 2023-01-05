<?php

namespace courseProject\src\Commands\FakeData;

use courseProject\src\Articles\Articles;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use \Faker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private ArticlesRepositoryInterface $articlesRepository
    )
    {
        parent::__construct();
    }

    protected function configue(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: '.$user->getUserLogin());
        }

        foreach ($users as $user) {
            for ($i = 0; $i < 20; $i++) {
                $article = $this->createFakeArticle($user);
                $output->writeln('Article created: '. $article->getHeader());
            }
        }
        return Command::SUCCESS;
    }

    private function createFakeUser(): Users
    {
        $user = Users::createFrom(
            $this->faker->userName,
            $this->faker->firstName,
            $this->faker->lastName,
            $this->faker->password
        );

        $this->usersRepository->save($user);

        return $user;
    }

    private function createFakeArticle(Users $author): Articles
    {
        $article = new Articles(
            UUID::random(),
            $author->getUuid(),
            $this->faker->sentence(6, true),
            $this->faker->realText
        );

        $this->articlesRepository->save($article);
        return $article;
    }

}