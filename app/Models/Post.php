<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $appends = [
        'url',
        'author'
    ];

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
//        static::creating(function ($post) {
//            $post->generateSlug();
//        });
    }

    public function generateSlug()
    {
        $this->slug = str_slug($this->title);
    }

    public function getUrlAttribute()
    {
        
    }

    public function getAuthorAttribute()
    {
        return $this->user->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isOwnedBy(User $user)
    {
        return ($this->user_id == $user->id);
    }
}