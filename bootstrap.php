<?php

use courseProject\src\Container\DIContainer;
use courseProject\src\Http\Actions\Auth\IdentificationInterface;
use courseProject\src\Http\Actions\Auth\JsonBodyUuidIdentification;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;
use courseProject\src\Repositories\UsersRepository\SqliteUsersRepository;
use courseProject\src\Repositories\UsersRepository\UsersRepositoryInterface;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__.'/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:'.__DIR__.'/'.$_SERVER['SQLITE_DB_PATH'])
);

$container->bind(
    ArticlesRepositoryInterface::class,
    SqliteArticlesRepository::class
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUuidIdentification::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);


$logger = (new Logger('project'));

if ($_SERVER['LOG_TO_FILES'] === 'yes') {
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__.'/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__.'/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false
        ));
}

if ($_SERVER['LOG_TO_CONSOLE' === 'yes']) {
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(
    LoggerInterface::class,
    $logger
);

return $container;