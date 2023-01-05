<?php

namespace courseProject\src\Commands\Articles;

use courseProject\src\Exceptions\ArticleNotFoundException;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeleteArticle extends Command
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('articles:delete')
            ->setDescription('Delete an article')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of an article to delete'
            )
        ->addOption(
            'check-existence',
            'c',
            InputOption::VALUE_NONE,
            'Check if article actually exists'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $question = new ConfirmationQuestion(
            'Delete article [Y/n]?',
            false
        );

        if(!$this->getHelper(`question`)
            ->ask($input, $output, $question))
        {
            return Command::SUCCESS;
        }

        $uuid = new UUID($input->getArgument('uuid'));

        if($input->getOption('check-existence')) {
            try {
                $this->articlesRepository->get($uuid);
            } catch (ArticleNotFoundException $e) {
                $output->writeln($e->getMessage());
                return Command::FAILURE;
            }
        }

        $this->articlesRepository->delete($uuid);

        $output->writeln("Article $uuid deleted");

        return Command::SUCCESS;
    }
}