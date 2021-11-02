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
        $products = Product::query()

            ->when($request->has('q'), function ($query) use ($request) {
                $query->where("name","like","%".$request->q."%")
                    ->Orwhere("description","like","%".$request->q."%");
            })
            ->when($request->has('discount'), function ($query) use ($request) {
                $query->where('discount','>', 0);
            })
            ->when($request->has('gift'), function ($query) use ($request) {
                $query->where('discount','>', 80);
            })
            ->when($request->has('hot'), function ($query) use ($request) {
                $query->where('is_hot',1);
            })
            ->when($request->has('free'), function ($query) use ($request) {
                $query->where('is_free_shipping',1);
            })
            ->when($request->has('sort'), function ($query) use ($request) {
                $query->orderBy('original_price',$request->sort);
            })
            ->paginate(20);
        
        if($products->total() > 0) {
            return responder()->success($products,ProductTransformer::class)->respond();
        }
        return responder()->error('404','not found')->respond(404);
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
