<?php

namespace courseProject\src\Http\Actions\Articles;

use courseProject\src\Repositories\SqliteArticlesRepository\ArticlesRepositoryInterface;
use courseProject\src\Repositories\SqliteArticlesRepository\SqliteArticlesRepository;

class FindByUuid
{
    public function __construct(
        private ArticlesRepositoryInterface $articlesRepository
    )
    {
    }


}