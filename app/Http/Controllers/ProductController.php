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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return HomeCollection
     */
    public function show($id)
    {
        $product = Product::find($id);
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }



}
