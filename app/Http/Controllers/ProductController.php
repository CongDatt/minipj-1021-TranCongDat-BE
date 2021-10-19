<?php

namespace App\Http\Controllers;

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
     * @param Request $request
     * @return HomeCollection
     */
    public function index(Request $request): HomeCollection
    {
            $query = Product::query();

            if($sort = $request->input('sort')) {
                $query->orderBy('original_price',$sort);
                return new HomeCollection($query->get());
            }

            if($discount = $request->input('discount')) {
                $query->orderBy('discount',$discount);
                return new HomeCollection($query->get());
            }

            if($q = $request->input('q')) {
                $query->whereRaw("name LIKE '%".$q."%'")
                    ->orderByRaw("description LIKE '%".$q."%'");
                return new HomeCollection($query->get());
            }

            else {
                return new HomeCollection(Product::paginate());
            }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'string',
            'is_free_shipping' => 'numeric',
            'category_id' => 'numeric',
            'order_id' => 'numeric',
            'original_price' => 'numeric',
            'is_gift' => 'numeric',
            'is_hot' => 'numeric',
            'discount' => 'numeric',
        ]);
        $product  = Product::create($request->all());

        if($request->hasFile('image')) {
            $path = $request->file('image')->store('images_dat','s3');
            $image = $product->files()->create([
                'file_name' => basename($path),
                'file_path' => Storage::disk('s3')->url($path),
                'disk' => 's3',
                'file_size' => $request->image->getSize(),
            ]);
        }
        foreach ($product->files as $file) {
            $product->path = $file->file_path;
        }

        if ($validator->fails()) {
            return responder()->error('422','Unauthorized')->respond(422);
        }

        return responder()->success($product,ProductTransformer::class)->respond();
    }

//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $product = Product::find($id);
        return responder()->success($product,ProductTransformer::class)->respond();
    }

//    /**
//     * @param Request $request
//     * @param $id
//     */
//    public function edit(Request $request, $id)
//    {
//    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'description' => 'string',
            'is_free_shipping' => 'numeric',
            'category_id' => 'numeric',
            'order_id' => 'numeric',
            'original_price' => 'numeric',
            'is_gift' => 'numeric',
            'is_hot' => 'numeric',
            'discount' => 'numeric',
        ]);
        $product = Product::find($id);
        $product->fill($request->all());
        $product->save();

        if ($validator->fails()) {
            return responder()->error('422','Unauthorized')->respond(422);
        }
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * @param $id
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function destroy($id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return responder()->success(['message' => 'Product deleted successfully']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function trash(): \Illuminate\Http\JsonResponse
    {
        $product = Product::onlyTrashed()->get();
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
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
