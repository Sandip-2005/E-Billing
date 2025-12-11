<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\InvoiceModel;
use App\Models\AddCustomer;

use Illuminate\Database\Eloquent\Model;

class PaymentsModel extends Model
{
    protected $table = 'payments';
    protected $fillable = ['invoice_id', 'customer_id', 'amount', 'payment_date', 'note', 'due_amount', 'payment_status'];
    public function invoice() { return $this->belongsTo(InvoiceModel::class, 'invoice_id'); }
    public function customer() { return $this->belongsTo(AddCustomer::class, 'customer_id'); }
}
