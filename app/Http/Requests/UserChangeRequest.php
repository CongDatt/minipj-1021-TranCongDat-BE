<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Flugg\Responder\Facades\Responder;

class UserChangeRequest extends FormRequest
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
        return [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'phone' => 'required|min:6',
            'gender'=>'string',
            'birthday'=>'string',
            'address' => 'string|min:10',
            'old_password' => 'string|min:6',
            'new_password' => 'string|min:6|confirmed',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            responder()->error(422)->data(['message'=>$validator->errors()])->respond(400)
        );
    }
}
