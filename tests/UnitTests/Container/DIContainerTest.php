<?php

namespace courseProject\tests\UnitTests\UnitTests\Container;

use courseProject\src\Container\DIContainer;
use courseProject\src\Container\NotFoundException;
use courseProject\src\Repositories\UsersRepository\InMemoryUsersRepository;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use PHPUnit\Framework\TestCase;

class DIContainerTest extends TestCase
{
    /**
     * @return void
     * @throws NotFoundException
     */
    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        $container = new DIContainer();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: courseProject\UnitTests\Container\SomeClass'
        );

        $container->get(SomeClass::class);
    }

    /**
     * @return void
     * @throws NotFoundException
     */
    public function testItResolvesClassWithoutDependecies(): void
    {
        $container = new DIContainer();
        $object = $container->get(SomeClassWithoutDependecies::class);

        $this->assertInstanceOf(
            SomeClassWithoutDependecies::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void
    {
        $container = new DIContainer();
        $container->bind(
            UsersRepositoryInterface::class,
            InMemoryUsersRepository::class
        );
        $object = $container->get(UsersRepositoryInterface::class);

        $this->assertInstanceOf(
            InMemoryUsersRepository::class,
            $object
        );
    }

    public function testItReturnsPredefinedObject(): void
    {
        $container = new DIContainer();
        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );

        $this->assertSame(42, $object->value());
    }

    public function testItResolvesClassWithDependencies(): void
    {
        $container = new DIContainer();
        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(ClassDependingOnAnoter::class);

        $this->assertInstanceOf(
            ClassDependingOnAnoter::class,
            $object
        );
    }
}