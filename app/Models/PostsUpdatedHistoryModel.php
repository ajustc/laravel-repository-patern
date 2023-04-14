<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsUpdatedHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'posts_updated_history';

    protected $fillable = [
        'user_id',
        'post_id',
        'name',
        'email',
        'created_at',
        'updated_at',
    ];

    public $timestamp = true;
}
