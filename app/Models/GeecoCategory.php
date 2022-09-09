<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeecoCategory extends Model
{
    protected $primaryKey = 'categoryId';
    protected $table = 'geecocategory';
    public $timestamps = false;
    protected $guarded = ['*'];
}
