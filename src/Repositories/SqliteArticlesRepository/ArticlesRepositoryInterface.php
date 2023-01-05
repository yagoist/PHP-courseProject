<?php

namespace courseProject\src\Repositories\SqliteArticlesRepository;

use courseProject\src\Articles\Articles;
use courseProject\src\UUID;

interface ArticlesRepositoryInterface
{
    public function save(Articles $user): void;

    public function get(UUID $uuid): Articles;

    public function delete(UUID $uuid): void;
}