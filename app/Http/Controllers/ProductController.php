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

    /**
     * create(): create product and up image to s3
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ProductRequest $productRequest)
    {
        $validated = $productRequest->validated();
        $product  = Product::create($validated);

        if($productRequest->hasFile('image')) {
            $path = $productRequest->file('image')->store('images_dat','s3');
            $image = $product->file()->create([
                'file_name' => basename($path),
                'file_path' => Storage::disk('s3')->url($path),
                'disk' => 's3',
                'file_size' => $productRequest->image->getSize(),
            ]);
        }
        else {
            $image = $product->file()->create([
                'file_name' => '',
                'file_path' => '',
                'disk' => 's3',
                'file_size' => '',
            ]);
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
     * update(): change some information in a product
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(ProductRequest $productRequest, $id)
    {
        $validated = $productRequest->validated();
        $product = Product::find($id);
        $product->update($validated);
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * destroy(): delete a product
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
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
     * @param $id
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function forceDelete($id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->forceDelete();
        return responder()->success(['message' => 'Product destroyed successfully']);
    }
}
