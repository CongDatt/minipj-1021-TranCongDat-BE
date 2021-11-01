<?php

namespace App\Services;

use App\Models\Product;
use AWS\CRT\HTTP\Request;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;
use Aws\S3\S3ClientInterface;
use App\Models\File;


class ImageService
{
    public function UploadImage($file){
        $path = $file->store('images_dat','s3');
        return File::create([
            'file_name' => basename($path),
            'file_path' => Storage::disk('s3')->url($path),
            'disk' => 's3',
            'file_size'=> $file->getSize(),
        ]);
    }

    public function DeleteImage($filePath)
    {
        if($filePath) {
            Storage::disk('s3')->delete($filePath);
            return responder()->success(['message' => 'file deleted successfully']);
        }
        return responder()->error(['message' => 'File not found'])->respond(404);
    }

    public function AttachImage(Product $product, $file) {
        return $product->file()->save($file);
    }

    public function DetachImage(Product $product) {
        return $product->file()->delete();
    }


}
