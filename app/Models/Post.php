<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Post
 * @package App\Models
 * @method static Post|Builder|\Illuminate\Database\Query\Builder forUser(User $user)
 */
class Post extends Model
{
    protected $appends = [
        'author'
    ];

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    protected $hidden = [
        'user',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($post) {
            $post->updateSlugIfTitleCharged();
        });
    }

    public function updateSlugIfTitleCharged()
    {
        if ($this->isDirty('title')) {
            $this->slug = $this->generateSlug();
        }
    }
    
    public function generateSlug()
    {
        $tried = 0;
        $base = str_slug($this->title);
        do {
            $query = $this->newQuery();

            // append counter if slug is already exists.
            $appending = $tried ? '-' . $tried: '';
            $slug = $base . $appending;

            // increment counter
            $tried++;
            $query->where('slug', $slug);
        } while ($query->exists());
        return $slug;
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

    public function scopeForUser(Builder $builder, User $user)
    {
        if ($user->isUser()) {
            $builder->where('user_id', $user->id);
        }

        return  $builder;
    }

    public function canUpdate(User $user)
    {
        return $user->isAdmin() || $this->isOwnedBy($user);
    }

    public static function findBySlug($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}