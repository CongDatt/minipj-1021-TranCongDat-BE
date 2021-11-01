<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Transformers\FileTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function store($file){
        if($file) {
            $path = $file->store('images_dat');
            return File::create([
                'file_name' => basename($path),
                'file_path' => Storage::disk('local')->url($path),
                'disk' => 's3',
                'file_size'=> $file->getSize(),
            ]);
        }

        return responder()->error()->respond('422');
    }
}
