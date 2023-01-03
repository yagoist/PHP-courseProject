<?php

namespace courseProject\src\Repositories\SqliteAuthTokensRepository;

use courseProject\src\AuthToken;

interface AuthTokensRepositoryInterface
{

    public function save(AuthToken $authToken): void;

    public function get(string $token): AuthToken;

}