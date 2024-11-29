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

    public function recordStockChange($quantity, $reason = null, $type = null)
    {
        $oldStock = $this->current_stock;
        $newStock = $type === 'in' 
            ? $this->current_stock + $quantity 
            : $this->current_stock - $quantity;

        // Update current stock
        $this->current_stock = $newStock;
        $this->save();

        // Create product history record
        $this->productHistories()->create([
            'changed_field' => 'current_stock',
            'old_value' => $oldStock,
            'new_value' => $newStock,
            'reason_changed' => $reason ?? ($type === 'in' ? 'Stock In' : 'Stock Out')
        ]);
    }
}