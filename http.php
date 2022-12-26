<?php

use courseProject\src\Http\Actions\Articles\CreateArticle;
use courseProject\src\Http\Actions\Comments\CreateComment;
use courseProject\src\Http\Actions\Users\CreateUser;
use courseProject\src\Http\Actions\Users\FindByUserLogin;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Exceptions\HttpException;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;
use courseProject\src\Repositories\SqliteCommentsRepository\SqliteCommentsRepository;
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
} catch (HttpException) {
    (new ErrorResponse())->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => new FindByUserLogin(
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'courseProject.sqlite')
            )
        ),
//    '/posts/show' => new FindByUuid(
//        new SqliteUsersRepository(
//    new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
//        )
//    ),
],
    'POST' => [
        '/posts/create' => new CreateArticle(
            new SqliteArticlesRepository(
                new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
        ),
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
            )
        ),
        '/users/create' => new CreateUser(
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
            )
        ),
        '/comments/create' => new CreateComment(
            new SqliteArticlesRepository(
                new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
            ),
            new SqliteUsersRepository(
                new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
            ),
            new SqliteCommentsRepository(
                new PDO('sqlite:'.__DIR__.'/identifier.sqlite')
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
    $response->send();
} catch (Exception $e) {
    (new ErrorResponse ($e->getMessage()))->send();
}

