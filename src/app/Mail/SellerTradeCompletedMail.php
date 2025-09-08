<?php

namespace App\Mail;

use App\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerTradeCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Purchase $purchase;

    public function __construct(Purchase $purchase)
    {

        $this->purchase = $purchase->load('product', 'user');
    }

    public function build()
    {
        return $this->subject('【COACHTECHフリマ】取引が完了しました')
            ->markdown('emails.seller.trade-completed');
    }
}
