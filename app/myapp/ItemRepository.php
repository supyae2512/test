<?php

namespace App\myapp;

use App\Pithos\common\GeneralRepository;

class ItemRepository extends GeneralRepository
{
    public function __construct()
    {
        parent::__construct(new Items());
    }


}
