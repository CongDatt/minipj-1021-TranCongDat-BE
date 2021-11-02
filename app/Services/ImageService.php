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
//    /**
//     * @param $file
//     * @return mixed
//     */
//    public function uploadImage($file){
//        $path = $file->store('images_dat');
//        $file = File::create([
//            'file_name' => basename($path),
//            'file_path' => Storage::disk('s3')->url($path),
//            'disk' => 's3',
//            'file_size'=> $file->getSize(),
//        ]);
//        return null;
//    }

    /**
     * @param $filePath
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder|\Illuminate\Http\JsonResponse
     */
    public function deleteImage($filePath)
    {
        if($filePath) {
            Storage::disk('local')->delete($filePath);
            return responder()->success(['message' => 'file deleted successfully']);
        }
        return responder()->error(['message' => 'File not found'])->respond(404);
    }

    public function attachImage(Product $product, $file): Product
    {
        $product->file()->save($file);
        return $product;
    }

    public function detachImage(Product $product) {
        return $product->file()->delete();
    }


}
