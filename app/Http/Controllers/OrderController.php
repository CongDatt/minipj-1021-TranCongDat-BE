<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Transformers\CategoryTransformer;
use App\Transformers\OrderTransformer;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $orders = Order::all();
        return responder()->success($orders,OrderTransformer::class)->respond();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $total = 0;
        $title = '';
        foreach ($request->carts as $cart){
            $total += $cart['price'];
            $title .= $cart['title'].",";
        }
        $title = substr($title, 0, -1);
        $order = Order::create([
           'title' => $title,
           'price' => $total
        ]);
        return responder()->success($order,OrderTransformer::class)->respond();
    }

}
