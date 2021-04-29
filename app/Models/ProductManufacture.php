<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductManufacture extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code_name',
    ];

    public function detail(){
        return $this->hasMany('App\Models\ProductDetail');
    }
}
