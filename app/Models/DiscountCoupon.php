<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'description', 'max_uses', 'max_uses_user', 'type', 'discount_amount', 'min_amount', 'status', 'starts_at', 'expires_at'];
}
