<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'description', 'type', 'value',
        'min_order', 'max_discount', 'usage_limit',
        'used_count', 'starts_at', 'expires_at', 'status',
    ];

    protected $casts = [
        'starts_at'  => 'date',
        'expires_at' => 'date',
    ];

    /** Check if coupon is valid for a given subtotal */
    public function isValid($subtotal = 0): bool
    {
        if ($this->status !== 'active') return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($subtotal < $this->min_order) return false;
        return true;
    }

    /** Calculate discount amount */
    public function discountAmount($subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return round($discount, 2);
        }
        return min((float) $this->value, $subtotal);
    }
}
