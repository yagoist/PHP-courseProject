<?php

namespace courseProject\src\Comments;

use courseProject\src\UUID;
use courseProject\src\Users\Users;
use courseProject\src\Articles\Articles;

class Comments
{
    public function __construct(
        private UUID $uuid,
        private UUID $authorId,
        private UUID $articlesId,
        private string $text
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

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function __toString(): string
    {
        return $this->text;
    }

}