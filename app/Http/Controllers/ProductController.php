<?php

namespace App\Http\Controllers;

use App\Actions\CreateProductAction;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\Slide;
use App\Transformers\LoginTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use App\Http\Resources\HomeCollection;
use App\Models\User;
use Validator;
use Flugg\Responder\Facades\Responder;
use Illuminate\Support\Facades\Storage;
use http\Client\Response;
use App\Models\File;
use App\Services\ImageService;
use App\Http\Controllers\UploadImageController;

class ProductController extends Controller
{

    /**
     * index(): show all product, search, sort product
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
            $query = Product::query();

            if($q = $request->input('q')) {
                $products = Product::where("name","like","%".$q."%")
                    ->orWhere("description","like","%".$q."%")->paginate(20);
                if($products->count() == 0) {
                    return responder()->error(['message' => 'Product not found'])->respond(404);
                }
                return responder()->success($products,ProductTransformer::class)->with('files')->respond();
            }
            else {
                $products = Product::paginate(20);
                return responder()->success($products,ProductTransformer::class)->respond();
            }
    }

    public function create(UploadImageController $uploadImageController,ProductRequest $productRequest,ImageService $imageService)
    {
        $data = $productRequest->validated();
        $productInformation = Product::create($data);
        $file = $uploadImageController->store($productRequest->file('image'));
        $product = $imageService->attachImage($productInformation, $file);
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * show(): show product detail
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::find($id);
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * @param ProductRequest $productRequest
     * @param ImageService $imageService
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateProductRequest $updateProductRequest, ImageService $imageService,UploadImageController $uploadImageController,$id): \Illuminate\Http\JsonResponse
    {
        $data = $updateProductRequest->validated();
        $productInformation = Product::find($id)->update($data);

        if($updateProductRequest->file('image')){
            $file = $uploadImageController->updateImage($updateProductRequest->file('image'), $id);
            $product = $imageService->attachImage(Product::find($id), $file);
            return responder()->success($product,ProductTransformer::class)->respond();
        }

        return responder()->success(Product::find($id),ProductTransformer::class)->respond();
    }

    /**
     * @param ImageService $imageService
     * @param $id
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function destroy(ImageService $imageService, $id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return responder()->success(['message' => 'Product deleted successfully']);
    }

    /**
     * restore(): restore deleted category
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * forceDelete(): destroy product without restore
     * @param ImageService $imageService
     * @param $id
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function forceDelete(ImageService $imageService, $id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $product = Product::withTrashed()->findOrFail($id);

        $imageService->deleteImage($product->file->file_path);
        $product->file()->delete();
        $product->forceDelete();

        return responder()->success(['message' => 'Product destroyed successfully']);
    }
}
