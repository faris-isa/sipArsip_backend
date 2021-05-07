<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OfferPurchase extends Pivot
{
    use HasFactory;
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'status',
        'created_at',
        'purchase_at',
        'done_at',
    ];
}
