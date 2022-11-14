<?php

namespace courseProject\src\Repositories\SqliteCommentsRepository;

use courseProject\src\Comments\Comments;
use courseProject\src\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comments $comment): void;

    public function get(UUID $uuid): Comments;
}