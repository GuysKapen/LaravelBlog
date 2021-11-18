<?php

namespace App\Models;

use Carbon\Traits\Date;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static User find(int|string|null $id)
 * @property string password
 * @property mixed $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $image
 * @property integer $role_id
 * @property string $about
 * @property string $remember_token
 * @property Date $email_verified_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin(): bool
    {
        return $this->role->id === 1;
    }

    public function favorite_posts()
    {
        return $this->belongsToMany(Post::class)->withTimestamps();
    }

    public function scopeAuthors($query)
    {
        return $query->where('role_id', 2);
    }
}
