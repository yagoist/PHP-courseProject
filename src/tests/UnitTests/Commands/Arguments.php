<?php

namespace courseProject\src\tests\UnitTests\Commands;

class Arguments
{
    public function __construct(
        private array $arg
    )
    {

    }

    /**
     * @param $argument
     * @return string
     */
    public function get($argument): string
    {
        return $this->arg[$argument];
    }

    public function __toString(): string
    {
        return $this->arg.'';
    }
}