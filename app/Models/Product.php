<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'initial_stock', 'current_stock'];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    // public function stocks(): HasMany
    // {
    //     return $this->hasMany(Stock::class);
    // }

}