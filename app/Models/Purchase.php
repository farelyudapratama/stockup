<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'purchase_date', 'total_amount'];

    protected $casts = [
        'purchase_date' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
    public function calculateTotalAmount()
    {
        $this->total_amount = $this->details->sum('subtotal');
        $this->save();
    }
}