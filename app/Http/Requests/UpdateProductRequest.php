<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Flugg\Responder\Facades\Responder;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'name' => 'string',
            'description' => 'string',
            'is_free_shipping' => 'numeric',
            'category_id' => 'numeric|exists:categories,id',
            'order_id' => 'numeric',
            'original_price' => 'numeric',
            'quantity' => 'numeric',
            'is_gift' => 'numeric',
            'is_hot' => 'numeric',
            'discount' => 'numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            responder()->error(403)->data(['message'=>$validator->errors()])->respond(403)
        );
    }
}
