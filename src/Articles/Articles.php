<?php

namespace src\Articles;


class Articles
{
    public function __construct(
        private int $id,
        private int $authorId,
        private string $header,
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
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
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
        return $this->header.'>>>'.$this->text;
    }

}