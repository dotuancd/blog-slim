<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post',
        'user',
        'content'
    ];

    public function setUserAttribute(User $user)
    {
        $this->user_id = $user->id;
        return $this;
    }

    public function setPostAttribute(Post $post)
    {
        $this->post_id = $post->id;
        return $this;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}