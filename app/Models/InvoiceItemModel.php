<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class InvoiceItemModel extends Model
{
    use HasFactory;

    protected $table = 'bill_items';

    protected $fillable = [
        'bill_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'line_total'
    ];

    public function bill()
    {
        return $this->belongsTo(InvoiceModel::class);
    }
}
