<?php
namespace App\myapp;

use App\Pithos\common\GeneralRepository;

class CartRepository extends GeneralRepository
{
    public function __construct()
    {
        parent::__construct(new Cart());
    }


}