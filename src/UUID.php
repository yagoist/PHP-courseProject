<?php

namespace courseProject\src;
use src\Exceptions\AppException;


class UUID
{
    public function __construct(
        private string $uuidString
    )
    {
        if (!uuid_is_valid($this->uuidString))
        {
            throw new AppException(
                "Malformed UUID: $this->uuidString"
            );
        }

    }

    public static function random(): self
    {
        return new self(uuid_create(UUID_TYPE_RANDOM));
    }

    public function __toString(): string
    {
        return $this->uuidString;
    }
}