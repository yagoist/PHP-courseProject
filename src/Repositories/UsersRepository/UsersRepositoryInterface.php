<?php

namespace src\Repositories\UsersRepository;

use src\Users\Users;
use src\UUID;

interface UsersRepositoryInterface
{
    public function save(Users $user): void;

    public function get(UUID $uuid): Users;

}