<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerShop extends Model
{
    protected $table = 'customershop';
    public $timestamps = false;
    protected $guarded = ['*'];
}
