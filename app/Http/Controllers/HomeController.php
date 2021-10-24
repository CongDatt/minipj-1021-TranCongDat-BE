<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Transformers\CategoryTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\HomeResource;
use App\Http\Resources\HomeCollection;
use Illuminate\Support\Facades\DB;
use Flugg\Responder\Facades\Responder;

class HomeController extends Controller
{
    /**
     * index(): return a product collection
     * @param Request $request
     * @return HomeCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if($sort = $request->input('sort')) {
            if($sort == 'discount') {
                $products= Product::where('discount','>','0')->paginate(20);
                return responder()->success($products,ProductTransformer::class)->respond();
            }
            $products = $query->orderBy('original_price',$sort)->paginate(20);
            return responder()->success($products,ProductTransformer::class)->respond();
        }
        if($q = $request->input('q')) {
            $products = Product::where("name","like","%".$q."%")
                ->orWhere("description","like","%".$q."%")->paginate(20);
            if($products->count() == 0) {
                return responder()->success(['message' => 'Product not found'])->respond(200);
            }
            return responder()->success($products,ProductTransformer::class)->respond();
        }
        $products = Product::paginate(20);
        return responder()->success($products,ProductTransformer::class)->respond();
    }

    /**
     * hotProduct() return a collection of hot products
     */
    public function hotProduct(): \Illuminate\Http\JsonResponse
    {
        $product = Product::where('is_hot',1)->paginate(20);
        return $this->success($product,ProductTransformer::class)->respond();
    }
    /**
     * giftProduct() return a collection of gift products
     */
    public function giftProduct(): \Illuminate\Http\JsonResponse
    {
        $product = Product::where('is_gift',1)->paginate(20);
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * freeShippingProduct() return a collection of free shipping products
     */
    public function freeShippingProduct(): \Illuminate\Http\JsonResponse
    {
        $product = Product::where('is_free_shipping',1)->paginate(20);
        return responder()->success($product,ProductTransformer::class)->respond();
    }

    /**
     * categoryList() return a collection of categories
     */
    public function categoryList()
    {
        return responder()->success(Category::paginate(20),CategoryTransformer::class)->respond();
    }


}
