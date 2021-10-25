<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slide;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use App\Transformers\SlideTransformer;

class SlideController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $slide = Slide::paginate(20);
        return responder()->success($slide,SlideTransformer::class)->respond();
    }

    /**
     * @param Slide $slide
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Slide $slide,$id): \Illuminate\Http\JsonResponse
    {
        $slide = Slide::find($id);
        return responder()->success($slide,SlideTransformer::class)->respond();
    }
}
