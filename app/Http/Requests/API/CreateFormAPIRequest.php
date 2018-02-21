<?php

namespace App\Http\Requests\API;

use App\Storage\Form\Form;
use InfyOm\Generator\Request\APIRequest;

class CreateFormAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'title' => 'required',
            'guid' => 'required|unique:forms',
            'description' => 'nullable',
            'template' => 'required|file',
        ];
    }
}
