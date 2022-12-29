<?php

namespace courseProject\src\Http;

use courseProject\src\Exceptions\JsonException;

/**
 *
 */
abstract class Response
{
    protected const SUCCESS = true;


    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();

        header('Content-Type: application/json');

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array
     */
    abstract protected function payload(): array;
}