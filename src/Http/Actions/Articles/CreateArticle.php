<?php

namespace courseProject\src\Http\Actions\Articles;

use courseProject\src\Articles\Articles;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Exceptions\InvalidArgumentException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Http\Actions\ActionInterface;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\Response;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\UUID;

class CreateArticle implements ActionInterface
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    public function handle(Request $request): Response
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newArticleUuid = UUID::random();

        try {
            $article = new Articles(
                $newArticleUuid,
                $authorUuid,
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->articlesRepository->save($article);

        return new SuccessfulResponse([
           'uuid' => (string)$newArticleUuid
        ]);
    }
}