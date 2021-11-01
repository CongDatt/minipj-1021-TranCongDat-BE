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
            'name' => 'string|between:2,100',
            'email' => 'string|email|unique:users|max:100',
            'phone' => 'min:6',
            'gender'=>'numeric',
            'birthday'=>'string',
            'address' => 'string|min:10',
            'old_password' => 'string|min:6',
            'new_password' => 'string|min:6',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            responder()->error(403)->data(['message'=>$validator->errors()])->respond(403)
        );
    }
}
