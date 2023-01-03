<?php

namespace courseProject\src;

use DateTimeImmutable;

class AuthToken
{
    public function __construct(
        private string $token,
        private UUID $uuid,
        private DateTimeImmutable $expiresOn
    )
    {
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpiresOn(): DateTimeImmutable
    {
        return $this->expiresOn;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->token;
    }

}