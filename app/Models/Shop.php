<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $primaryKey = 'shopId';
    protected $table = 'shop';
    public $timestamps = false;
    protected $guarded = ['*'];
}
