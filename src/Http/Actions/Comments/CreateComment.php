<?php

namespace courseProject\src\Http\Actions\Comments;

use courseProject\src\Comments\Comments;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Exceptions\InvalidArgumentException;
use courseProject\src\Exceptions\UserNotFoundException;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\Response;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\SqliteCommentsRepository\CommentsRepositoryInterface;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use courseProject\src\UUID;

class CreateComment
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository,
        private UsersRepositoryInterface $usersRepository,
        private CommentsRepositoryInterface $commentsRepository
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

        try {
            $articleUuid = new UUID($request->jsonBodyField('article_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $this->articlesRepository->get($articleUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $newCommentUuid = UUID::random();

        try {
            $comment = new Comments(
                $newCommentUuid,
                $authorUuid,
                $articleUuid,
                $request->jsonBodyField('text')
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);

        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid
        ]);
    }
}