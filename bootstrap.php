<?php

use courseProject\src\Container\DIContainer;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;
use courseProject\src\Repositories\UsersRepository\SqliteUsersRepository;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;

require_once __DIR__.'/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
);

$container->bind(
    ArticlesRepositoryInterface::class,
    SqliteArticlesRepository::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

return $container;