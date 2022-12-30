<?php

namespace src\Articles;


use src\UUID;

class Articles
{
    public function __construct(
        private UUID $uuid,
        private UUID $authorId,
        private string $header,
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