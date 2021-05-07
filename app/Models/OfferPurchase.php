<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OfferPurchase extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'status',
        'created_at',
        'purchase_at',
        'done_at',
    ];
}
