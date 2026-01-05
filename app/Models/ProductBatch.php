<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'batch_no',
        'expiry_date',
        'quantity',
        'purchase_price',
        'selling_price'
    ];

    public function product()
    {
        return $this->belongsTo(ProductModel::class);
    }
}
