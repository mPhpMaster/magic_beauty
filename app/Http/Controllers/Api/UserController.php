<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceTokenResource;
use App\Http\Resources\UserResource;
use App\Interfaces\IRoleConst;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function show(Request $request): JsonResource
    {
        return apiJsonResource($request->user(), UserResource::class, true);
    }
    public function getDeviceToken(Request $request, User $user): JsonResource
    {
        return apiJsonResource($user, DeviceTokenResource::class, true);
    }

    public function update(Request $request): JsonResource
    {
        /** @var User $user */
        $user = $request->user();
        $data = $request->validate([
            'name_en' => ['nullable', 'string', 'max:255'],
            'name_ar' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile' => ['nullable', 'numeric', 'unique:users,mobile,' . $user->id],
            'location' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
            'image' => ['nullable'],
        ]);

        if ( !empty($data) ) {
            if ( isset($data['password']) ) {
                $data['password'] = Hash::make($data['password']);
            }
            if ( isset($data['image']) ) {
                array_pull($data, 'image');
            }
            $user->update($data);
            if( $request->hasFile('image') ) {
                $user->addImage($request->file('image') );
            }
        }

        return apiJsonResource($user, UserResource::class, true);
    }

    public function register(Request $request): JsonResource
    {
        $data = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'numeric', 'unique:users,mobile'],
            'location' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
            'image' => ['nullable'],
        ]);
        if ( isset($data['password']) ) {
            $data['password'] = Hash::make($data['password']);
        }
        if ( isset($data['image']) ) {
            array_pull($data, 'image');
        }
        $user = User::create($data);
        $user->assignRole(IRoleConst::PATIENT_ROLE);

        if ( $request->hasFile('image') ) {
            $user->addImage($request->file('image'));
        }

        return apiJsonResource($user, UserResource::class, true);
    }
}
