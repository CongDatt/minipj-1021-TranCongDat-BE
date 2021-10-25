<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\HomeResource;
use App\Http\Resources\HomeCollection;

class HomeController extends Controller
{
    /**
     * hotProduct() return a collection of hot products
     */
    public function hotProduct(): HomeCollection
    {
        $product = Product::where('is_hot',1)->get();
        return new HomeCollection($product);
    }
    /**
     * giftProduct() return a collection of gift products
     */
    public function giftProduct(): HomeCollection
    {
        $product = Product::where('is_gift',1)->get();
        return new HomeCollection($product);
    }

    /**
     * freeShippingProduct() return a collection of free shipping products
     */
    public function freeShippingProduct(): HomeCollection
    {
        $product = Product::where('is_free_shipping',1)->get();
        return new HomeCollection($product);
    }

    /**
     * categoryList() return a collection of categories
     */
    public function categoryList(): HomeCollection
    {
        return new HomeCollection(Category::all());
    }


}
