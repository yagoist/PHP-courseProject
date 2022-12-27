<?php

use courseProject\src\Http\Actions\Articles\CreateArticle;
use courseProject\src\Http\Actions\Articles\FindByUuid;
use courseProject\src\Http\Actions\Comments\CreateComment;
use courseProject\src\Http\Actions\Users\CreateUser;
use courseProject\src\Http\Actions\Users\FindByUserLogin;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;
use courseProject\src\Repositories\SqliteCommentsRepository\SqliteCommentsRepository;
use courseProject\src\Repositories\UsersRepository\SqliteUsersRepository;

//require_once __DIR__.'/vendor/autoload.php';

$container = require __DIR__.'/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);


try {
    $path = $request->path();
} catch (HttpException) {
    (new ErrorResponse())->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse())->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUserLogin::class,
        '/posts/show' => FindByUuid::class
],
    'POST' => [
        '/posts/create' => CreateArticle::class,
        '/users/create' => CreateUser::class,
        '/comments/create' => CreateComment::class
    ]
];

if (!array_key_exists($path, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
    $response->send();
} catch (Exception $e) {
    (new ErrorResponse ($e->getMessage()))->send();
}

