<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    protected $fillable = [
        'user_id',
        'payment_receipt',
        'qr_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
