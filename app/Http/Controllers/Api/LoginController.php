<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResource;
use App\Http\Resources\LogoutResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Api
 */
class LoginController extends Controller
{
    public function login(Request $request): JsonResource
    {
        $data = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if ( !($user = User::by($this->username(), $data['login'])->byActive()->first()) || !Hash::check($data['password'], $user->password) ) {
            throw ValidationException::withMessages([
                'login' => [__('auth.failed')],
            ]);
        }

        /** @var User $user */
        $token = $user->createToken('login');

        return apiJsonResource([
            'user' => $user->withAccessToken($token->accessToken),
            'token' => $token->plainTextToken
        ], LoginResource::class);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'mobile';
    }

    public function logout(Request $request): JsonResource
    {
        $deleted = false;
        /** @var User $user */
        if ( $user = $request->user() ) {
            /** @var \Illuminate\Database\Eloquent\Model $accessToken */
            $deleted = ($accessToken = $user->currentAccessToken()) ? $accessToken->delete() : false;
        }

        return apiJsonResource($deleted, LogoutResource::class, !!$deleted);
    }

    public function refreshToken(Request $request): JsonResource
    {
        /** @var User $user */
        $token = ($user = $request->user())->createToken('login');

        return apiJsonResource([
            'user' => $user->withAccessToken($token->accessToken),
            'token' => $token->plainTextToken
        ], LoginResource::class);
    }
}
