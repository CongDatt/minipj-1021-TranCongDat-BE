<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeCollection;
use App\Models\Category;
use App\Models\Product;
use App\Transformers\CategoryTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $category = Category::all();
        return responder()->success($category,CategoryTransformer::class)->respond();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'category_name' => 'required|string',
        ]);

        $category = Category::create($request->all());
        return responder()->success($category,CategoryTransformer::class)->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $products = Category::find($id)->products;
        return responder()->success($products,ProductTransformer::class)->respond();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'category_name' => 'required|string',
        ]);
        $category = Category::find($id);
        $category->slug = null;
        $category->update($request->all());
        return responder()->success($category,CategoryTransformer::class)->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
