<?php

namespace App\Http\Controllers;
use App\Http\Resources\HomeCollection;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserChangeRequest;
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
use Illuminate\Support\Facades\Hash;
use App\Transformers\LoginTransformer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Validator;


class AuthController extends Controller
{
    /**
     * register(): creating a new user
     * @param UserRequest $userRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $userRequest): \Illuminate\Http\JsonResponse
    {
        $validated = $userRequest->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        return responder()->success($user,SuccessTransformer::class)->respond();
    }

    /**
     * login(): accessing a user to system
     * @param Request $userChangeRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $userChangeRequest): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($userChangeRequest->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return responder()->error(422)->data(['message'=>$validator->errors()])->respond(400);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return responder()->error('422','You have entered an invalid email or password')->respond(400);
        }
        $data = auth()->user();
        $data->token = $token;
        return responder()->success(auth()->user(),LoginTransformer::class)->respond();
    }

    /**
     * changeInfo(): changing information's a user
     * @param Request $userChangeRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeInfo(UserChangeRequest $userChangeRequest): \Illuminate\Http\JsonResponse
    {
        $validator = $userChangeRequest->validated();
        $userId = auth()->user()->id;
        $userPass = auth()->user()->password;
        $oldPass = $userChangeRequest->old_password;

        if($oldPass) {
            if(Hash::check($oldPass, $userPass)) {
                $user = User::where('id',$userId)->update([
                        'password' => bcrypt($userChangeRequest->new_password),
                        'name' => $userChangeRequest->name,
                        'phone' => $userChangeRequest->phone,
                        'email' => $userChangeRequest->email,
                        'address' => $userChangeRequest->address,
                        'gender'=> $userChangeRequest->gender,
                        'birthday'=> $userChangeRequest->birthday,
                    ]
                );
                $data = User::find($userId);
                return responder()->success($data,SuccessTransformer::class)->respond();
            }
            return responder()->error('403','Your old password do not match or forgot fill the token')->respond(403);
        }
        $user = User::where('id',$userId)->update([
                'name' => $userChangeRequest->name,
                'phone' => $userChangeRequest->phone,
                'email' => $userChangeRequest->email,
                'address' => $userChangeRequest->address,
                'gender'=> $userChangeRequest->gender,
                'birthday'=> $userChangeRequest->birthday,
            ]
        );
        $data = User::find($userId);
        return responder()->success($data,SuccessTransformer::class)->respond();
    }

    /**
     * logout(): existing a user
     * @param Request $request
     * @return \Flugg\Responder\Http\Responses\SuccessResponseBuilder
     */
    public function logout(Request $request) {
        auth()->logout();
        return responder()->success(['message' => 'User successfully signed out']);
    }

    /**
     * userProfile(): return information's a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile(): \Illuminate\Http\JsonResponse
    {
        return responder()->success(auth()->user(),SuccessTransformer::class)->respond();
    }

    /**
     * productFavorite(): return a list of favorite products
     * @return HomeCollection
     */
    public function productFavorite(): HomeCollection
    {
        $userId = auth()->user()->id;
        $products = User::find($userId)->products;
        return new HomeCollection($products);
    }

}
