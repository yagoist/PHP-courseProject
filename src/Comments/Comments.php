<?php

namespace src\Comments;



class Comments
{
    public function __construct(
        private int $id,
        private int $authorId,
        private int $articlesId,
        private string $text
    )
    {

    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return int
     */
    public function getArticlesId(): int
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