<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsModel extends Model
{
    use HasFactory;

    protected $table = 'posts';
    
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'image',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public $timestamp = true;

    public function comment()
    {
        return $this->hasMany(CommentsModel::class, 'post_id');
    }

    public function user_comment() {
        return $this->hasOne(User::class, 'id');
    }
}
