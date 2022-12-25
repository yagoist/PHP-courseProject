<?php

use courseProject\src\Http\Actions\Users\FindByUserLogin;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Http\SuccessfulResponse;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Repositories\UsersRepository\SqliteUsersRepository;

require_once __DIR__.'/vendor/autoload.php';

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
} catch (HttpExeception) {
    (new ErrorResponse)->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => new FindByUserLogin(
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'/blog.sqlite')
        )
    ),
//    '/posts/show' => new FindByUuid(
//        new SqliteUsersRepository(
//    new PDO('sqlite:'.__DIR__.'/blog.sqlite')
//        )
//    ),
],
    'POST' => [
        '/posts/create' => new CreatePost(
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'/blog.sqlite')
        ),
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'/blog.sqlite')
            )
        ),
    ]
];

if (!array_key_exists($path, $routes)) {
    (new ErrorResponse('Not found'))->send();
    return;
}

if (!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse('Not found'))->send();
    return;
}

$action = $routes[$method][$path];

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    (new ErrorResponse ($e->getMessage()))->send();
}

$response->send();