<?php

namespace courseProject\src\Repositories\SqliteLikesRepository;

use courseProject\src\Articles\Articles;
use courseProject\src\Likes\Like;
use courseProject\src\UUID;

interface LikesRepositoryInterface
{
    public function save(Like $like): void;

    public function getByArticleUuid(UUID $uuid): Articles;
}