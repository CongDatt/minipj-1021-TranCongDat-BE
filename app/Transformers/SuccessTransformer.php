<?php

namespace App\Transformers;

use App\Models\User;
use Flugg\Responder\Transformers\Transformer;

class SuccessTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'phone' => (string) $user->phone,
            'address' => (string) $user->address,
            'gender' => (string) $user->gender,
            'birthday' => (string) $user->birthday,
        ];
    }
}
