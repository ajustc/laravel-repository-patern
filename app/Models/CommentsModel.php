<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsModel extends Model
{
    use HasFactory;

    protected $table = 'comments';
    
    protected $fillable = [
        'user_id',
        'post_id',
        'text',
        'created_at',
        'updated_at'
    ];

    public $timestamp = true;

    public function posts() {
        return $this->belongsTo(PostsModel::class, 'id');
    }
}
