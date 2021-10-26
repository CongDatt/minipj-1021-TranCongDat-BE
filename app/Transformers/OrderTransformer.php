<?php

namespace App\Transformers;

use App\Models\Order;
use Flugg\Responder\Transformers\Transformer;
use function Livewire\str;

class OrderTransformer extends Transformer
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
     * @param  \App\Models\Order $order
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'id' => (int) $order->id,
            'date_buy' => (string) $order->date_buy,
            'products' => (string) $order->title,
            'total_price' => (int) $order->price,
            'status' => 0,
            'user_id' => $userId = auth()->user()->id,
        ];
    }
}
