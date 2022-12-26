<?php declare(strict_types=1);

namespace courseProject\src\Http;

class SuccessfulResponse extends Response
{
    protected const SUCCESS = true;

    public function __construct(
        private array $data = []
    )
    {
    }

    /**
     * @return array
     */
    protected function payload(): array
    {
        return ['data' => $this->data];
    }
}