<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    /** @var array $fillable */
    protected $fillable = [
        'postable_id',
        'postable_type',
        'user_id',
        'user_name',
        'user_email',
        'is_visible',
    ];
    /** @var array $casts */
    protected $casts = [
        'is_visible' => 'bool',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault(function ($user, $comment) {
            $user->name = $comment->user_name;
            $user->email = $comment->user_email;
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePostIs(Builder $query, int $id): Builder
    {
        return $query->where('post_id', $id);
    }

    public function scopeIsVisible(Builder $query): Builder
    {
        return $query->where('is_visible', 1);
    }

    public function scopeIsNotVisible(Builder $query): Builder
    {
        return $query->where('is_visible', 0);
    }
}