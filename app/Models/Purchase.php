<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'status'
    ];

    public function offers(){
        return $this->belongsToMany('App\Models\Offer')
        ->withPivot('status')
        ->withTimestamps();
    }

    public function products(){
        return $this->belongsToMany('App\Models\Product')
        ->withPivot('serial_number', 'tanggal_beli', 'masa_garansi', 'lokasi', 'tanggal_selesai')
        ->withTimestamps();
    }
}
