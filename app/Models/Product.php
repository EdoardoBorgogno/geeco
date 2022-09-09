<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'productId';
    protected $table = 'products';
    public $timestamps = false;
    protected $guarded = ['*'];
}
