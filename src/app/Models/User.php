<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * 代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_first_login',
        'profile_image_path',
        'postal_code',
        'address',
        'building_name',
    ];

    /**
     * 属性を隠す配列（シリアライズ時）
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * キャストする属性
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoritedByProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorites', 'user_id', 'product_id');
    }



    /**
     * リレーション: ユーザーのレビュー
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * リレーション: ユーザーの購入
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function getProfileImagePathAttribute()
    {
        return $this->attributes['profile_image_path']
            ? asset('storage/' . $this->attributes['profile_image_path'])
            : asset('images/default-avatar.png');
    }


    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id');
    }
}
