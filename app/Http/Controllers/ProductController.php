<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
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

    public function create(ProductRequest $productRequest, ImageService $imageService)
    {
        $validated = $productRequest->validated();
        $product  = Product::create($validated);

        if($productRequest->hasFile('image')) {
            $file = $productRequest->file('image');
            $fileUploaded = $imageService->UploadImage($file);
            $fileAttached = $imageService->AttachImage($product, $fileUploaded);
            return responder()->success($product,ProductTransformer::class)->respond();
        }
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

    public function update(ProductRequest $productRequest, ImageService $imageService, $id): \Illuminate\Http\JsonResponse
    {
        $validated = $productRequest->validated();
        $product = Product::find($id)->update($validated);

        if($productRequest->hasFile('image')) {
            $file = $productRequest->file('image');
            $fileUploaded = $imageService->UploadImage($file);
            $fileAttached = $imageService->AttachImage($product, $fileUploaded);
            return responder()->success($product,ProductTransformer::class)->respond();
        }
        return responder()->success($product,ProductTransformer::class)->respond();
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
     * trash(): get all deleted product
     * @return \Illuminate\Http\JsonResponse
     */
    public function trash(): \Illuminate\Http\JsonResponse
    {
        $product = Product::onlyTrashed()->get();
        return responder()->success($product,ProductTransformer::class)->respond();
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
        $imageService->DeleteImage($product->file->file_path);
        $imageService->DetachImage($product);
        $product->forceDelete();
        return responder()->success(['message' => 'Product destroyed successfully']);
    }
}
