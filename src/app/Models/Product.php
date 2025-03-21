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
        'status',
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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

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
