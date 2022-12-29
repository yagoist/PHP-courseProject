<?php

namespace courseProject\src\Http\Actions\Articles;

use courseProject\src\Articles\Articles;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Exceptions\InvalidArgumentException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Http\Actions\ActionInterface;
use courseProject\src\Http\Actions\Auth\IdentificationInterface;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\Response;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\UUID;
use Psr\Log\LoggerInterface;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private IdentificationInterface $identification,
        private LoggerInterface $logger
    )
    {
    }

    public function handle(Request $request): Response
    {
        $author = $this->identification->user($request);

        $newArticleUuid = UUID::random();

        try {
            $article = new Articles(
                $newArticleUuid,
                $author->getUuid(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->articlesRepository->save($article);

        $this->logger->info("Post created: $newArticleUuid");

        return new SuccessfulResponse([
           'uuid' => (string)$newArticleUuid
        ]);
    }
}