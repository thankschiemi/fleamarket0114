<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $fillable = [
        'user_id',
        'product_id',
        'purchase_date',
        'status',
        'payment_method',
        'is_rated',
        'is_rated_by_seller',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
