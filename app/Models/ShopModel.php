<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ShopModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'shop_profile';
    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_contact',
        'gst_number',
        'shop_address',
        'shop_logo'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
