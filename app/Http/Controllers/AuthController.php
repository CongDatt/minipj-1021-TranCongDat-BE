<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\HomeCollection;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserChangeRequest;
use App\Transformers\ProductTransformer;
use App\Transformers\UserTransformer;
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
        $user = User::create($validated);
        return responder()->success($user,UserTransformer::class)->respond();
    }

    /**
     * login(): login a user to system
     * @param Request $userChangeRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $loginRequest)
    {
        $validated = $loginRequest->validated();
        if (!$token = auth()->attempt($validated)) {
            return responder()->error('401','You have entered an invalid email or password')->respond(401);
        }
        return responder()->success($this->createNewToken($token))->respond();
    }

    /**
     * changeInfo(): changing information's a user
     * @param Request $userChangeRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeInfo(UserChangeRequest $userChangeRequest)
    {
        $validated = $userChangeRequest->validated();
        $user = User::find(auth()->user()->id);
        $user->update($validated);
        return $user;
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
        return responder()->success(auth()->user(),UserTransformer::class)->respond();
    }

    /**
     * productFavorite(): return a list of favorite products
     * @return \Illuminate\Http\JsonResponse
     */
    public function productFavorite(): \Illuminate\Http\JsonResponse
    {
        $user = User::find(auth()->user()->id);
        $products = $user->products()->paginate(20);
        return responder()->success($products,ProductTransformer::class)->respond();
    }

    protected function createNewToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }

}
