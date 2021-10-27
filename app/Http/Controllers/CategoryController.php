<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
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
     * index(): show all categories
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $category = Category::paginate(20);
        return responder()->success($category,CategoryTransformer::class)->respond();
    }

    /**
     * create(): create a new category
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CategoryRequest $categoryRequest): \Illuminate\Http\JsonResponse
    {
        $category = Category::create($categoryRequest->validated());
        return responder()->success($category,CategoryTransformer::class)->respond();
    }

    /**
     * show(): show category
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(CategoryRequest $categoryRequest,$id): \Illuminate\Http\JsonResponse
    {
        $products = Category::find($id)->products();
        if($sort = $categoryRequest->input('sort')) {
            $products = $products->orderBy('original_price',$sort)->paginate(20);
            return responder()->success($products,ProductTransformer::class)->respond();
        }
        if($sort = $categoryRequest->input('discount')) {
            $products = $products->orderBy('discount',$sort)->paginate(20);
            return responder()->success($products,ProductTransformer::class)->respond();
        }
        return responder()->success($products->paginate(20),ProductTransformer::class)->respond();
    }

    /**
     * update(): update category information's category
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $categoryRequest, $id)
    {
        $category = Category::find($id);
        $category->slug = null;
        $category->update($categoryRequest->all());
        return responder()->success($category,CategoryTransformer::class)->respond();
    }

    /**
     * destroy(): delete a category
     * @param $id
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function destroy($id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $product = Category::findOrFail($id);
        $product->delete();
        return responder()->success(['message' => 'Category deleted successfully']);
    }

    /**
     * trash(): get all deleted categories
     * @return \Illuminate\Http\JsonResponse
     */
    public function trash(): \Illuminate\Http\JsonResponse
    {
        $product = Category::onlyTrashed()->paginate(20);
        return responder()->success($product,CategoryTransformer::class)->respond();
    }

    /**
     * restore(): restore a deleted category
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        $product = Category::withTrashed()->findOrFail($id);
        $product->restore();
        return responder()->success($product,CategoryTransformer::class)->respond();
    }

    /**
     * forceDelete(): destroy category without restore
     * @param $id
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function forceDelete($id): \Flugg\Responder\Http\Responses\SuccessResponseBuilder
    {
        $product = Category::withTrashed()->findOrFail($id);
        $product->forceDelete();
        return responder()->success(['message' => 'Category destroyed successfully']);
    }
}
