<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'admin_table';
    protected $fillable = ['admin_id','name', 'email', 'password'];

    public function admin()
    {
        return $this->hasMany(AdminModel::class, 'admin_id');
    }
}
