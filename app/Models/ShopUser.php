<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    protected $primaryKey = 'userId';
    protected $table = 'shopusers';
    public $timestamps = false;
    protected $guarded = ['*'];
}
