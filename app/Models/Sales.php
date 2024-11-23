<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_name', 'sale_date', 'total_amount'];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function calculateTotalAmount()
    {
        $this->total_amount = $this->details->sum('subtotal');
        $this->save();
    }
}