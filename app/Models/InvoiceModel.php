<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id', 'shop_id', 'customer_id', 'shop_phone', 'shop_gst',
        'shop_address', 'bill_date', 'sub_total', 'tax', 'discount', 'total', 'status', 'due_amount', 'payment_mode'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItemModel::class, 'bill_id');
    }

    public function payments()
    {
        return $this->hasMany(PaymentsModel::class, 'invoice_id');
    }

    public function shop()
    {
        return $this->belongsTo(ShopModel::class);
    }

    public function customer()
    {
        return $this->belongsTo(AddCustomer::class, 'customer_id');
    }

    // Accessor for total paid amount
    public function getPaidAmountAttribute()
    {
        return $this->payments->sum('amount');
    }

    // Accessor for current due amount
    public function getDueAmountAttribute()
    {
        return $this->total - $this->getPaidAmountAttribute();
    }

    public function scopePendingInvoices($query)
    {
        // Assuming 'pending' invoices are those that are not 'draft' and not yet fully 'paid'.
        return $query->whereIn('status', ['final', 'sent', 'partial']);
    }
}
