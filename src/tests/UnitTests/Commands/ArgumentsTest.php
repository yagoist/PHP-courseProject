<?php

namespace courseProject\src\tests\UnitTests\Commands;

use PHPUnit\Framework\TestCase;


class ArgumentsTest extends TestCase
{

    public function testItReturnsArgumentsValueByName(): void
    {
        $arguments = new Arguments(['some_key' => 'some_value']);

        $value = $arguments->get('some_key');

        $this->assertEquals('some_value', $value);

        $this->assertIsString($value);
    }

}