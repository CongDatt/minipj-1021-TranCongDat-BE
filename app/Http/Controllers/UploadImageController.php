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
        $path = $file->store('images_dat');
        return File::Create([
            'file_name' => $path,
            'file_path' => Storage::disk('local')->url($path),
            'disk' => 'local',
            'file_size'=> $file->getSize(),
        ]);
    }

    public function updateImage($file, $id) {
        $path = $file->store('images_dat');
        $oldFile = File::where('fileable_id',$id)->first();

        $uploadedFile = File::Create([
            'file_name' => $path,
            'file_path' => Storage::disk('local')->url($file->store('images_dat')),
            'disk' => 'local',
            'file_size'=> $file->getSize(),
        ]);

        $imageService = new ImageService();
        $imageService->deleteImage($oldFile->file_path);
        $oldFile->delete();

        return $uploadedFile;
    }
}
