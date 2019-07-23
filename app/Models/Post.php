<?php

namespace App\Models;

use App\Auth\HasPolicy;
use Carbon\Carbon;
use App\Auth\Contracts\HasPolicy as PolicyContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Post
 *
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property int $user_id
 * @property string $status_as_text
 * @property User $user
 * @property Carbon published_at
 * @property int approved_by
 * @property \Illuminate\Support\Carbon approved_at
 * @property int status
 *
 * @mixin \Illuminate\Database\Query\Builder|Builder
 * @method static Post|Builder|\Illuminate\Database\Query\Builder forUser(User $user)
 * @method static Post | Builder | \Illuminate\Database\Query\Builder whereSlug($slug = null)
 * @method static Post | Builder | \Illuminate\Database\Query\Builder withUnpublished()
 * @method static Post | Builder | \Illuminate\Database\Query\Builder published()
 */
class Post extends Model implements PolicyContract
{
    use HasPolicy;

    const STATUS_DRAFT = 1;
    const STATUS_UNDER_REVIEW = 3;
    const STATUS_PUBLISHED = 2;

    const ABILITY_APPROVE = 'approve';
    const ABILITY_PUBLISH = 'publish';

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
        'pivot',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($post) {
            /** @var self $post */
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

    public function writePolicy(User $user)
    {
        return $user->isAdmin() || $this->isOwnedBy($user);
    }

    public static function findBySlug($slug)
    {
        $instance = new static();
        return $instance->where('slug', $slug)->firstOrFail();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function recentComments($numOfComments =5)
    {
        return $this->comments()->latest()->limit($numOfComments);
    }

    public function newest()
    {
        return $this->newQuery()->where('published_at', '>', $this->published_at)->oldest();
    }

    public function oldest()
    {
        return $this->newQuery()->where('published_at', '<', $this->published_at)->latest();
    }

    public function requestReview()
    {
        $this->status = static::STATUS_UNDER_REVIEW;
        $this->save();
    }

    public function publish()
    {
        $this->status = static::STATUS_PUBLISHED;
        $this->published_at = $this->freshTimestamp();
        $this->save();
    }

    public function approve(User $reviewer)
    {
        $this->approved_by = $reviewer->id;
        $this->approved_at = $this->freshTimestamp();
        $this->save();
    }

    public function userCanApprove(User $user)
    {
        return $user->isAdmin();
    }

    public function userCanPublish(User $user)
    {
        return $user->isAdmin();
    }
}