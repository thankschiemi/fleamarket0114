<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'brand',
    ];

    public function getImagePathAttribute()
    {
        $imagePath = $this->attributes['img_url'] ?? null;

        if ($imagePath) {
            if (!str_starts_with($imagePath, 'products/') && !str_starts_with($imagePath, 'images/')) {
                $imagePath = 'images/' . $imagePath;
            }
            return asset('storage/' . $imagePath);
        }

        return asset('images/default-product.jpg');
    }


    // リレーション: 商品のレビュー
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
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
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id');
    }
}
