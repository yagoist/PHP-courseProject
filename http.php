<?php

use courseProject\src\Http\Actions\Articles\CreateArticle;
use courseProject\src\Http\Actions\Articles\FindByUuid;
use courseProject\src\Http\Actions\Auth\LogIn;
use courseProject\src\Http\Actions\Comments\CreateComment;
use courseProject\src\Http\Actions\Users\CreateUser;
use courseProject\src\Http\Actions\Users\FindByUserLogin;
use courseProject\src\Http\ErrorResponse;
use courseProject\src\Http\Request;
use courseProject\src\Exceptions\HttpException;
use Psr\Log\LoggerInterface;

//require_once __DIR__.'/vendor/autoload.php';

$container = require __DIR__.'/bootstrap.php';


$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input')
);

$logger = $container->get(LoggerInterface::class);

try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse())->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse())->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUserLogin::class,
        '/posts/show' => FindByUuid::class
],
    'POST' => [
        '/login' => LogIn::class,
        '/posts/create' => CreateArticle::class,
        '/users/create' => CreateUser::class,
        '/comments/create' => CreateComment::class
    ]
];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}

$actionClassName = $routes[$method][$path];

try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);

} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e])
    (new ErrorResponse())->send();
}

$response->send();

