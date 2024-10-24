<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHistory extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'changed_field', 'old_value', 'new_value', 'reason_changed'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}