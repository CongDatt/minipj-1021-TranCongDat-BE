<?php

namespace App\Http\Controllers;
use App\Http\Resources\HomeCollection;
use App\Transformers\SuccessTransformer;
use Flugg\Responder\Facades\Responder;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Testing\Fluent\Concerns\Has;
use phpDocumentor\Reflection\Type;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\JWTAuth;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Transformers\LoginTransformer;
use App\Http\Requests\RegisterRequest;


class AuthController extends Controller
{
    /**
     * login(): to login a user and return a
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => Hash::make($request->password)]
        ));
        return responder()->success($user,SuccessTransformer::class)->respond();
    }

    /**
     * register(): to register a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return responder()->error('422','Unauthorized')->respond(422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return responder()->error('401','Unauthorized')->respond(401);
        }
        $data = auth()->user();
        $data->token = $token;
        return responder()->success(auth()->user(),LoginTransformer::class)->respond();
    }

    /**
     * changeInfo(): to change information's a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeInfo(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100',
            'phone' => 'required|string|min:6',
            'gender'=>'string',
            'birthday'=>'string',
            'address' => 'string|min:10',
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $userId = auth()->user()->id;
        $userPass = auth()->user()->password;
        $oldPass = $request->old_password;

        if(Hash::check($oldPass, $userPass)) {
            $user = User::where('id',$userId)->update([
                    'password' => bcrypt($request->new_password),
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'address' => $request->address,
                    'gender'=> $request->gender,
                    'birthday'=> $request->birthday,
                ]
            );
            $data = User::find($userId);
            return responder()->success($data,SuccessTransformer::class)->respond();
        }
        return responder()->error('403','Your old password do not match or forgot fill the token')->respond(403);
    }

    /**
     * logout(): to exist a user
     * @param Request $request
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function logout(Request $request) {
        auth()->logout();
        return responder()->success(['message' => 'User successfully signed out']);
    }


    /**
     * userProfile(): to return information's a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(): \Illuminate\Http\JsonResponse
    {
        return responder()->success(auth()->user(),SuccessTransformer::class)->respond();
    }


    public function productFavorite()
    {
        $userId = auth()->user()->id;
        $products = User::find($userId)->products;
        return new HomeCollection($products);
    }

}
