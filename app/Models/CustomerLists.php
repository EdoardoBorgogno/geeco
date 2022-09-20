<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLists extends Model
{
    protected $table = 'customerlists';
    public $timestamps = false;
    protected $guarded = ['*'];
}
