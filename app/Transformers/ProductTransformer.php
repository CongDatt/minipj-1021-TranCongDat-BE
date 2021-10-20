<?php

namespace App\Transformers;

use App\Models\Product;
use App\Transformers\FileTransformer;
use Flugg\Responder\Transformers\Transformer;


class ProductTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [
        'file' => FileTransformer::class,
    ];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id' => (int) $product->id,
            'name' => (string) $product->name,
            'description' => (string) $product->description,
            'img_path' => (string) $product->path,
            'is_free_shipping' => (int) $product->is_free_shipping,
            'category_id' => (int) $product->category_id,
            'order_id' => (int) $product->order_id,
            'quantity' => (int) $product->quantity,
            'original_price' => (int) $product->original_price,
            'is_gift' => (int) $product->is_gift,
            'is_hot' => (int) $product->is_hot,
            'discount' => (int) $product->discount,
        ];
    }
}
