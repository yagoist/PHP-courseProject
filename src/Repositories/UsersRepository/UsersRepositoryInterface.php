<?php

namespace courseProject\src\Repositories\UsersRepository;

use courseProject\src\Users\Users;
use courseProject\src\UUID;

interface UsersRepositoryInterface
{
    public function save(Users $user): void;

    public function get(UUID $uuid): Users;

}