<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed|integer $user_id
 * @property mixed|string $title
 * @property mixed|string $body
 * @property mixed|string $image
 * @property mixed|boolean $status
 * @property mixed|boolean $is_approved
 * @property mixed|string $slug
 * @property mixed|integer $view_count
 * @property integer $id
 * @method static Post find($id)
 * @method static where(string $string, false $false)
 */
class Post extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function favorite_to_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', 1);
    }
}
