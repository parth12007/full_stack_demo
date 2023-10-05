<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'client_id', 'email', 'phone_number', 'name', 'comment',
    ];

    /**
     * Save store credit gift card request to database
     * 
     * @param array $data
     * 
     * @return void
     */

    public static function saveCommentRequest(array $data)
    {
        $comment = new self;

        Log::info("Save data:- " . json_encode($data) . " in store credit table :- " . getCurrentTimeForLog());

        return $comment->create($data);
    }

}
