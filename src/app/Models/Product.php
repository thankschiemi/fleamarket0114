<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'description',
        'img_url',
        'condition',
        'user_id',
        'is_sold',
    ];

    public function getImagePathAttribute()
    {
        // storage/images/ ディレクトリを基にフルURLを返す
        return asset('storage/images/' . $this->img_url);
    }
    // リレーション: 商品のレビュー
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // リレーション: 商品のお気に入り
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }

    // リレーション: 商品の購入履歴
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    // リレーション: 商品の出品者 (Userモデルと関連付け)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
