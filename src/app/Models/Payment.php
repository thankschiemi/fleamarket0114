<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


    protected $table = 'payments';
    protected $fillable = [
        'purchase_id',
        'amount',
        'payment_method',
        'payment_status',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
