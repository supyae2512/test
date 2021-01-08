<?php

namespace App\myapp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'price', 'image', 'description'];

    protected $table = 'items';

    protected $dates = ['deleted_at'];

}
