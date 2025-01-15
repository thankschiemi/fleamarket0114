<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * 代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'img_url',
        'condition',
    ];

    /**
     * リレーション: 商品のレビュー
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * リレーション: 商品のお気に入り
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * リレーション: 商品の購入履歴
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
