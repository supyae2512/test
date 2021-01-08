<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CommonRequest {
    protected $rules = [];

    protected $editRules = [];

    /**
     * CommonRequest constructor.
     * @param array $rules
     * @param array $editRules
     */
    public function __construct(array $rules = [], array $editRules = [])
    {
        $this->rules = $rules;
        $this->editRules = $editRules;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return object
     */
    public function formValidate()
    {
        $validator = Validator::make(Input::all(), $this->getRules());
        if ($validator->fails()) {
            return $validator;
        }
    }

    /**
     * @return array
     */
    public function getEditRules()
    {
        return $this->editRules;
    }

    /**
     * @param array $editRules
     */
    public function setEditRules($editRules)
    {
        $this->editRules = $editRules;
    }

    /**
     * @return object
     */
    public function editFormValidate()
    {
        $editFormValidator = Validator::make(Input::all(), $this->getEditRules());
        if ($editFormValidator->fails()) {
            return $editFormValidator;
        }
    }
}
