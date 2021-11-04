<?php

namespace App\Actions;
use App\Models\File;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class CreateProductAction
{
    public function excute($data, $file) {
        $product = Product::query()->create($data);
        $path = $file->store('images_dat');
        $image = $product->file()->create([
            'file_name' => basename($path),
            'file_path' => Storage::disk('local')->url($path),
            'disk' => 's3',
            'file_size'=> $file->getSize(),
        ]);
        return $product;
    }
}
