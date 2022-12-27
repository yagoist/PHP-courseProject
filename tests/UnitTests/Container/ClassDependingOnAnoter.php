<?php

namespace courseProject\tests\UnitTests\UnitTests\Container;

class ClassDependingOnAnoter
{
    public function __construct(
        private SomeClassWithoutDependecies $one,
        private SomeClassWithParameter $two
    )
    {
    }
}