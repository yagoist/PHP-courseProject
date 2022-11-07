<?php

require_once __DIR__. '/vendor/autoload.php';

use src\UUID;
use src\Articles\Articles;
use src\Comments\Comments;
use src\Users\Users;
use src\Repositories\UsersRepository\SqliteUsersRepository;


$connection = new PDO('sqlite:'.__DIR__.'/identifier.sqlite');

$userRepository = new SqliteUsersRepository($connection);

$userRepository->save(new Users(UUID::random(), 'MegaIvan', 'Ivan', 'Ivanov'));
$userRepository->save(new Users(UUID::random(), 'TotalNikita', 'Nikita', 'VsyoPobrito'));

//try {
//    $user = $userRepository->getUser(123);
//    echo $user->getUserName();
//} catch (UserNotFoundException $e) {
//    echo $e->getMessage();
//}

//$faker = Faker\Factory::create();
//
//
//if (isset($argv[1])) {
//    switch ($argv[1]) {
//        case 'user' :
//            $user = new Users($faker->imei, $faker->firstName, $faker->lastName);
//            var_dump($user).PHP_EOL;
//            break;
//        case 'post' :
//            $post = new Articles($faker->imei, $faker->imei, $faker->paragraph, $faker->text);
//            var_dump($post).PHP_EOL;
//            break;
//        case 'comment' :
//            $comment = new Comments($faker->imei, $faker->imei, $faker->imei, $faker->text);
//            var_dump($comment).PHP_EOL;
//            break;
//        default:
//            echo 'неизвестная команда';
//    }
//}
