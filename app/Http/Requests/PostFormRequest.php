<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PostFormRequest extends Request
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        if ($this->user()->canPost()) {
            return true;
        } else {
            return false;
        }
    }

    // Get the validation rules that apply to the request.
    public function rules()
    {
        return [
            'title' => 'required|unique:posts|max:255',
            'title' => array('Regex:/^[A-Za-z0-9 ]+$/'),
            'body' => 'required'
        ];
    }
}