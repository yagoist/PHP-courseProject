<?php

use courseProject\src\Container\DIContainer;
use courseProject\src\Http\Actions\Auth\AuthentificationInterface;
use courseProject\src\Http\Actions\Auth\BearerTokenAuthentication;
use courseProject\src\Http\Actions\Auth\JsonBodyUuidAuthentification;
use courseProject\src\Http\Actions\Auth\PasswordAuthentication;
use courseProject\src\Http\Actions\Auth\PasswordAuthenticationInterface;
use courseProject\src\Http\Actions\Auth\TokenAuthenticationInterface;
use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;
use courseProject\src\Repositories\SqliteAuthTokensRepository\AuthTokensRepositoryInterface;
use courseProject\src\Repositories\SqliteAuthTokensRepository\SqliteAuthTokensRepository;
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
  TokenAuthenticationInterface::class,
  BearerTokenAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);

$container->bind(
    ArticlesRepositoryInterface::class,
    SqliteArticlesRepository::class
);

$container->bind(
    AuthentificationInterface::class,
    JsonBodyUuidAuthentification::class
);

$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);

//$container->bind(
//    AuthentificationInterface::class,
//    PasswordAuthentication::class
//);


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