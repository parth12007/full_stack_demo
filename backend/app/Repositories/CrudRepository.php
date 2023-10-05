<?php

namespace App\Repositories;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CrudRepository
{
    public function saveDataToComment(array $data)
    {
        try {
            return Comment::saveCommentRequest($data);
        } catch (\Exception $e) {
            Log::error("Something wrong while saving comment into database " . $e->getMessage());
            throw $e;
        }
    }

    public function updateDataToComment($comment, array $data)
    {
        try {
            return $comment->update($data);
        } catch (\Exception $e) {
            Log::error("Something wrong while updating comment into database " . $e->getMessage());
            throw $e;
        }
    }
}
