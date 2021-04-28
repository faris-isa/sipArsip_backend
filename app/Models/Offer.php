<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pembeli',
        'total_biaya',
    ];

    public function products(){
        return $this->belongsToMany('App\Models\Product')
        ->withPivot('qty', 'harga')
        ->withTimestamps();
    }

    public function purchases(){
        return $this->belongsToMany('App\Models\Purchase')
        ->withPivot('status')
        ->withTimestamps();
    }
}
