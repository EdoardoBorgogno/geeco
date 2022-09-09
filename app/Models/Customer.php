<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customerId';
    protected $table = 'customer';
    public $timestamps = false;
    protected $guarded = ['*'];
}
