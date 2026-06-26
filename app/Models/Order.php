<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'order_number',
    'customer_name',
    'customer_email',
    'customer_phone',
    'customer_address',
    'payment_method',
    'subtotal',
    'service_fee',
    'delivery_fee',
    'tax',
    'total',
    'status',
    'courier',
    'tracking_number',
    'eta'
])]
class Order extends Model
{
    use HasFactory;

    protected $table = 'ec_orders';

    /**
     * Get the items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Get the user who placed this order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
