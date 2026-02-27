<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Post
 *
 * Representasi artikel berita.
 */
class Post extends Model
{
    use SoftDeletes;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'user_id',
        'editor_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'is_breaking_news',
        'published_at',
        'scheduled_at',
        'views_count',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'status' => PostStatus::class,
        'is_breaking_news' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(PostHistory::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes (Performa Optimized)
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PostStatus::Published);
    }

    public function scopeBreaking(Builder $query): Builder
    {
        return $query->where('is_breaking_news', true);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->whereNotNull('scheduled_at')
                     ->where('scheduled_at', '<=', now());
    }

    public function scopeTrending(Builder $query): Builder
    {
        return $query->orderByDesc('views_count');
    }
}