<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'initial_stock', 'current_stock'];

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }
    public function productHistories()
    {
        return $this->hasMany(ProductHistory::class)->orderBy('created_at', 'desc');
    }

    public function salesDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}