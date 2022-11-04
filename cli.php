<?php

require_once __DIR__. '/vendor/autoload.php';

use src\Articles\Articles;
use src\Comments\Comments;
use src\Users\Users;

$faker = Faker\Factory::create();


if (isset($argv[1])) {
    switch ($argv[1]) {
        case 'user' :
            $user = new Users($faker->imei, $faker->firstName, $faker->lastName);
            echo $user.PHP_EOL;
            break;
        case 'post' :
            $post = new Articles($faker->imei, $faker->imei, $faker->paragraph, $faker->text);
            echo $post.PHP_EOL;
            break;
        case 'comment' :
            $comment = new Comments($faker->imei, $faker->imei, $faker->imei, $faker->text);
            echo $comment.PHP_EOL;
            break;
        default:
            echo 'неизвестная команда';
    }
}
