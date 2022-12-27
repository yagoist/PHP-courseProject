<?php

namespace courseProject\src\Likes;

use courseProject\src\UUID;

class Like
{
    public function __construct(
        private UUID $uuid,
        private UUID $articlesId,
        private UUID $authorId,
    )
    {
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return UUID
     */
    public function getAuthorId(): UUID
    {
        return $this->authorId;
    }

    /**
     * @return UUID
     */
    public function getArticlesId(): UUID
    {
        return $this->articlesId;
    }

    public function __toString(): string
    {
        return $this->text;
    }

}