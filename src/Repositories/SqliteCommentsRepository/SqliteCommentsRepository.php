<?php

namespace src\Repositories\SqliteCommentsRepository;

use PDO;
use src\Comments\Comments;
use src\UUID;

class SqliteCommentsRepository
{
    public function __construct(
        private PDO $connection
    )
    {

    }

    public function save(Comments $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, author_id, article_id, text)
                    VALUES (:uuid, :author_id, :article_id, :text)'
        );
        $statement->execute([
            ':uuid' => $comment->getUuid(),
            ':author_id' => $comment->getAuthorId(),
            ':article_id' => $comment->getArticlesId(),
            ':text' => $comment->getText(),
        ]);
    }
    public function get(UUID $uuid): Comments
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );
        $statement->execute([
            ':uuid' => (string)$uuid
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException(
                "cannot get user: $uuid"
            );
        }

        return new Comments(
            new UUID($result['uuid']),
            new UUID($result['author_id']),
            new UUID($result['articles_id']),
            $result['text']
        );
    }
}