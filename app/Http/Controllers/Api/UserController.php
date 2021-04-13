<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function show(Request $request): JsonResource
    {
        return apiJsonResource($request->user(), UserResource::class, true);
    }

    public function update(Request $request): JsonResource
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile' => ['nullable', 'numeric', 'unique:users,mobile,' . $user->id],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
        ]);
        if ( !empty($data) ) {
            if ( isset($data['password']) ) {
                $data['password'] = Hash::make($data['password']);
            }
            $user->update($data);
        }

        return apiJsonResource($user, UserResource::class, true);
    }
}
