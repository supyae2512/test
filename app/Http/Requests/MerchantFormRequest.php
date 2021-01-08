<?php

namespace App\Http\Requests;


class MerchantFormRequest extends CommonRequest
{
    public function __construct()
    {
        $rules = [
            'email'    => 'required|email|max:255|unique:merchants',
            'password' => 'required'
        ];

        $editRules = [
            'email'    => 'required|email|max:255',
            'password' => 'required'
        ];

        parent::__construct($rules, $editRules);
    }
}
