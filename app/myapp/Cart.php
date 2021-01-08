<?php

namespace App\myapp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $fillable = ['item_id', 'user_id', 'status'];

    protected $table = 'items';

    protected $dates = ['deleted_at'];

}
