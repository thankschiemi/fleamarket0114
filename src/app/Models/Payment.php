<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * 代入可能な属性
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id',
        'amount',
        'payment_method',
        'payment_status',
    ];

    /**
     * リレーション: 支払いに関連する購入情報
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
