<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'user_id', 'shop_id', 'product_id', 'product_name', 'price', 'quantity'
    ];

    public function shop()
    {
        return $this->belongsTo(ShopModel::class, 'shop_id');
    }
}
