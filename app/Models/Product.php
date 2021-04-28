<?php

namespace App\Models;

// use App\Models\OfferDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_produk',
        'model_produk',
        'type_products',
        'harga_satuan',
    ];

    public function poeswtproduct(){
        return $this->hasOne(PoeswtProduct::class);
    }

    public function nvrproduct(){
        return $this->hasMany(NvrProduct::class);
    }

    public function ipcamproduct(){
        return $this->hasMany(IpcamProduct::class);
    }

    public function offers(){
        return $this->belongsToMany('App\Models\Offer')
        ->withPivot('qty', 'harga')
        ->withTimestamps();
    }


}
