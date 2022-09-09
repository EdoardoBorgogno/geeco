<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $table = 'shopcategory';
    protected $primaryKey = 'shopCategoryId';
    public $timestamps = false;
    protected $guarded = ["*"];
}
