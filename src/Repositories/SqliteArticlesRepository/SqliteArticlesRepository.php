<?php

namespace courseProject\src\Repositories\SqliteArticlesRepository;

use courseProject\src\Exceptions\UserNotFoundException;
use PDO;
use courseProject\src\Articles\Articles;
use courseProject\src\Comments\Comments;
use courseProject\src\Users\Users;
use courseProject\src\UUID;
use Psr\Log\LoggerInterface;

class SqliteArticlesRepository implements ArticlesRepositoryInterface
{

    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    )
    {
    }

    public function save(Articles $articles): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO articles (uuid, author_id, header, text)
                    VALUES (:uuid, :author_id, :header, :text)
                    ON CONFLICT (uuid) DO UPDATE SET
                    header = :header,
                    text = :text'
        );
        $statement->execute([
            ':uuid' => (string)$articles->getUuid(),
            ':author_id' => $articles->getAuthorId(),
            ':header' => $articles->getHeader(),
            ':text' => $articles->getText(),

        ]);

        $this->logger->info("Post recorded in SQL: {$articles->getUuid()}");
    }
    public function get(UUID $uuid): Articles
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = ?'
        );

        $statement->execute([
            $uuid,
        ]);


        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new UserNotFoundException(
                "cannot get user: $uuid"
            );
        }

        return new Articles(
            new UUID($result['uuid']),
            new UUID($result['author_id']),
            $result['header'],
            $result['text']
        );

        $this->logger->warning("Post get from SQL: $uuid");
    }
}