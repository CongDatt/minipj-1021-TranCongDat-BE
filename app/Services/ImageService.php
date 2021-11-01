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
    /**
     * UploadImage(): to upload an image to aws s3
     * @param $file
     * @return mixed
     */
    public function UploadImage($file){
        $path = $file->store('images_dat','s3');
        return File::create([
            'file_name' => basename($path),
            'file_path' => Storage::disk('s3')->url($path),
            'disk' => 's3',
            'file_size'=> $file->getSize(),
        ]);
    }

    /**
     * DeleteImage(): to remove an image from aws s3
     * @param $filePath
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder|\Illuminate\Http\JsonResponse
     */
    public function DeleteImage($filePath)
    {
        if($filePath) {
            Storage::disk('s3')->delete($filePath);
            return responder()->success(['message' => 'file deleted successfully']);
        }
        return responder()->error(['message' => 'File not found'])->respond(404);
    }

    /**
     * AttachImage(): to attach image to a product
     * @param Product $product
     * @param $file
     * @return false|\Illuminate\Database\Eloquent\Model
     */
    public function AttachImage(Product $product, $file) {
        return $product->file()->save($file);
    }

    /**
     * DetachImage(): to detach image to a product
     * @param Product $product
     * @return mixed
     */
    public function DetachImage(Product $product) {
        return $product->file()->delete();
    }


}
