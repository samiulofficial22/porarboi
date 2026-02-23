<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'selected_format',
        'quantity',
        'transaction_id',
        'sender_number',
        'amount',
        'shipping_charge',
        'payment_method',
        'payment_status',
        'order_status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_district',
        'shipping_postcode',
        'delivery_note',
        'payment_screenshot',
        'approved_at',
        'download_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
