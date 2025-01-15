<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * 代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'purchase_date',
        'status',
    ];

    /**
     * リレーション: 購入を行ったユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーション: 購入された商品
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * リレーション: 購入に関連する支払い情報
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
