<?php

namespace src\Repositories\SqliteArticlesRepository;

use src\Articles\Articles;
use src\UUID;

interface ArticlesRepositoryInterface
{
    public function save(Articles $user): void;

    public function get(UUID $uuid): Articles;
}