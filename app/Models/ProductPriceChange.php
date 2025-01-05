<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceChange extends Model 
{     
    use HasFactory;      
    protected $fillable = ['product_id', 'price', 'quantity']; // tambah quantity     
    
    public function product()     
    {         
        return $this->belongsTo(Product::class);     
    } 
}