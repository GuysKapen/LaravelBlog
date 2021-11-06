<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static Category find(int $id)
 * @property mixed|string $name
 * @property mixed|string $slug
 * @property mixed|string $image
 */
class Category extends Model
{
    use HasFactory;

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
