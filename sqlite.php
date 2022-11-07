<?php


$connection = new PDO('sqlite:courseProject.sqlite');

$connection->exec(
    "INSERT INTO users (uuid, login, user_name, user_surname) VALUES ('1','Mamakana', 'Ivan', 'Ivanov')"
);

