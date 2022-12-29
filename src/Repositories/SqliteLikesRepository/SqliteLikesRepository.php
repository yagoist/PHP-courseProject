<?php

namespace courseProject\src\Repositories\SqliteLikesRepository;

use courseProject\src\Articles\Articles;
use courseProject\src\Comments\Comments;
use courseProject\src\Exceptions\AppException;
use courseProject\src\Exceptions\LikesNotFoundException;
use courseProject\src\Likes\Like;
use courseProject\src\UUID;
use PDO;

class SqliteLikesRepository implements LikesRepositoryInterface
{

    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (uuid, article_id, user_uuid)
                    VALUES (:uuid, :article_id, :user_uuid)
                    ON CONFLICT (uuid) DO UPDATE SET'
        );
        $statement->execute([
            ':uuid' => $like->getUuid(),
            ':article_id' => $like->getArticlesId(),
            ':user_uuid' => $like->getAuthorId(),
        ]);
    }
    public function get(UUID $uuid): Like
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE uuid = ?'
        );
        $statement->execute([
            $uuid
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new LikesNotFoundException(
                "cannot get like: $uuid"
            );
        }

        return new Like(
            new UUID($result['uuid']),
            new UUID($result['article_uuid']),
            new UUID($result['user_uuid'])
        );
    }

    /**
     * @param UUID $uuid
     * @return Articles
     * @throws LikesNotFoundException
     * @throws AppException
     */
    public function getByArticleUuid(UUID $uuid): Articles
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE article_uuid = ?'
        );
        $statement->execute([
            $uuid
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new LikesNotFoundException(
                "cannot get likes for article: $uuid"
            );
        }

        return new Articles(
            new UUID($result['uuid']),
            new UUID($result['author_id']),
            $result['header'],
            $result['text']
        );
    }
}