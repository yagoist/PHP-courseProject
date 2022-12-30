<?php

namespace UnitTests\Commands;

use courseProject\src\Commands\Arguments;
use courseProject\src\Exceptions\ArgumentException;
use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase
{

    /**
     * @throws ArgumentException
     */
    public function testItReturnsArgumentsValueByName(): void
    {
        $arguments = new Arguments(['some_key' => 'some_value']);

        $value = $arguments->get('some_key');

        $this->assertEquals('some_value', $value);

        $this->assertIsString($value);
    }

    /**
     * @throws ArgumentException
     */
    public function testItReturnsValuesAsString(): void
    {
        $arguments = new Arguments(['some_key' => 123]);

        $values = $arguments->get('some_key');

        $this->assertEquals(123, $values);
    }

    public function testThrowsAnExceptionWhenArgumentIsAbsent(): void
    {
        $arguments = new Arguments([]);

        $this->expectException(ArgumentException::class);

        $this->expectExceptionMessage("No such argument: some_key");

        $arguments->get('some_key');
    }

    public function argumentsProvider(): iterable
    {
        return [
          ['some_string', 'some_string'],
          [' some_string', 'some_string'],
          ['some_string ', 'some_string'],
          [123, '123'],
          [12.3, '12.3']
        ];
    }

    /**
     * @dataProvider argumentsProvider
     * @throws ArgumentException
     */
    public function testItConvertsArgumentsToString(
        $inputValue,
        $expectedValue
    ): void
    {
        $arguments = new Arguments(['some_key' => $inputValue]);

        $value = $arguments->get('some_key');

        $this->assertEquals($expectedValue, $value);
    }
}