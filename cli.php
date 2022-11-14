<?php

require_once __DIR__. '/vendor/autoload.php';

use courseProject\src\Articles\Articles;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;
use courseProject\src\Repositories\SqliteCommentsRepository\SqliteCommentsRepository;
use courseProject\src\Repositories\UsersRepository\SqliteUsersRepository;
use courseProject\src\UUID;


$connection = new PDO('sqlite:'.__DIR__.'/identifier.sqlite');
//$faker = new Faker\Factory::create();


$userRepository = new SqliteUsersRepository($connection);
$commentsRepository = new SqliteCommentsRepository($connection);
$articleRepository = new SqliteArticlesRepository($connection);

//$userRepository->save(new Users(UUID::random(), 'MegaIvan2', 'Ivan', 'Ivanov'));
//$userRepository->save(new Users(UUID::random(), 'TotalNikita2', 'Nikita', 'VsyoPobrito'));

//$articleRepository->save(
//    new Articles(
//        UUID::random(),
//        new UUID('17c134f0-a916-4ac6-ab1b-5c5f660553cb'),
//        'header',
//        'some text'
//    ));
//
//$commentsRepository->save(
//    new Comments(
//        UUID::random(),
//        new UUID('17c134f0-a916-4ac6-ab1b-5c5f660553cb'),
//        new UUID('2c1cdf50-cdd2-4036-a189-948a533a6f37'),
//        'somecomment'
//    ));


$post = $userRepository->get(new UUID('17c134f0-a916-4ac6-ab1b-5c5f660553cb'));
print_r($post);