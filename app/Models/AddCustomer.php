<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class AddCustomer extends Authenticatable
{
    use HasFactory;
    protected $table = 'add_customer';
    protected $fillable = ['user_id','customer_name', 'email', 'phone_number', 'address'];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

}
