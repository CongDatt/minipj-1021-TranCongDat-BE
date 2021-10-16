<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\HomeCollection;
use App\Models\User;

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        $userId = auth()->user()->id;
        $userId = 2;
        $products = User::find($userId)->products;

//        foreach ($products as $product) {
//            echo $product->name;
//        }
        return new HomeCollection($products);
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
