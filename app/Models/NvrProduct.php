<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NvrProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'in_bandwidth',
        'out_bandwidth',
        'channel_dicoding',
        'four_k_support',
        'sata_int',
        'network_int',
        'e_sata',
        'poe_ports',
        'hdmi_out',
        'deskripsi_produk',
        'foto_produk',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
