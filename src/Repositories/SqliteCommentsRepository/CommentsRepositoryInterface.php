<?php

namespace src\Repositories\SqliteCommentsRepository;

use src\Comments\Comments;
use src\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comments $comment): void;

    public function get(UUID $uuid): Comments;
}