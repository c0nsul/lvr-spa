<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeskStoreRequest extends FormRequest
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
        //req
        //max 255
        //uniq name
        //uniq deks
        //check except current desk id
        return [
            'name' => 'required|max:255|unique:desks,name,'.$this->desk->id,
        ];
    }

    /**
     * @return string[]
     */
    public function messages()
    {
        return [
            'name.unique' => 'Desk already exist!',
        ];
    }
}
