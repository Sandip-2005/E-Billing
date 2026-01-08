<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory;
    protected $table='user_signup';
    protected $fillable=[
        'name',
        'username',
        'email',
        'phone',
        'pancard',
        'gstin',
        'user_image',
        'address',
        'ps',
        'district',
        'state',
        'pincode',
        'password',
        'is_verified'
    ];

    public function customers()
    {
        return $this->hasMany(AddCustomer::class, 'user_id');
    }

    public function shops()
    {
        return $this->hasMany(ShopModel::class, 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(InvoiceModel::class, 'user_id');
    }

    public function scopePendingInvoices($query)
    {
        // Assuming 'pending' invoices are those that are not 'draft' and not yet fully 'paid'.
        return $query->whereIn('status', ['final', 'sent', 'partial']);
    }
}
