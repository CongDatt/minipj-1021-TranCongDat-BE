<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Services\ImageService;
use App\Transformers\FileTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function store($file){
        $path = $file->store('images_dat','s3');
        return File::Create([
            'file_name' => $path,
            'file_path' => Storage::disk('s3')->url($path),
            'disk' => 's3',
            'file_size'=> $file->getSize(),
        ]);
    }

    public function updateImage($file, $id) {
        $path = $file->store('images_dat','s3');

        $oldFile = File::where('fileable_id', $id)->first();
        (new ImageService)->deleteImage($oldFile->file_path);

        return File::Create([
            'file_name' => $path,
            'file_path' => Storage::disk('s3')->url($path),
            'disk' => 's3',
            'file_size'=> $file->getSize(),
        ]);
    }
}
