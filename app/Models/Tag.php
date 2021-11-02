<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string $name
 * @property mixed|string $slug
 * @method static Tag find(int $id)
 */
class Tag extends Model
{
    use HasFactory;
}
