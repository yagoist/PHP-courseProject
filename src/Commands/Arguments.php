<?php

namespace courseProject\src\Commands;

use courseProject\src\Exceptions\ArgumentException;

final class Arguments
{
    private array $arguments = [];

    public function __construct(iterable $arguments)
    {
        foreach ($arguments as $argument => $value)
        {
            $stringValue = trim((string)$value);

            if(empty($stringValue)) {
                continue;
            }

            $this->arguments[(string)$argument] = $stringValue;
        }
    }

    /**
     * @param array $argv
     * @return $this
     */
    public static function fromArgv(array $argv): self
    {
        $arguments = [];

        foreach ($argv as $argument)
        {
            $parts = explode('=', $argument);
            if(count($parts) !== 2) {
                continue;
            }
            $arguments[$parts[0]] = $parts[1];
        }
        return new self($arguments);
    }

    /**
     * @throws ArgumentException
     */
    public function get(string $argument): string
    {
        if(!array_key_exists($argument, $this->arguments))
        {
            throw new ArgumentException(
                "No such argument: $argument"
            );
        }
        return $this->arguments[$argument];
    }
}