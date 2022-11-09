<?php

namespace src\Repositories\SqliteArticlesRepository;

use PDO;
use src\Articles\Articles;
use src\Comments\Comments;
use src\Users\Users;
use src\UUID;

class SqliteArticlesRepository
{

    public function __construct(
        private PDO $connection
    )
    {

    }

    public function save(Articles $articles): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO articles (uuid, author_id, header, text)
                    VALUES (:uuid, :author_id, :header, :text)'
        );
        $statement->execute([
            ':uuid' => (string)$articles->getUuid(),
            ':author_id' => $articles->getAuthorId(),
            ':header' => $articles->getHeader(),
            ':text' => $articles->getText(),

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
            $result['header'],
            $result['text']
        );
    }
}