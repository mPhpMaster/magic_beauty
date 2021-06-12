<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Interfaces\IRoleConst;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $users = User::onlyPatients();
        if ( $status = $request->get('status') ) {
            $users->byStatus(User::getStatusId($status)->first());
        }
        return PatientResource::collection($users->latest()->get());
    }

    public function search_for_patient(Request $request): JsonResource
    {
        $data = $request->validate([
            'keyword' => ['nullable', 'string'],
        ]);

        $results = User::ByActive('users')->onlyPatients();
        if ( $data['keyword'] ) {
            $results = $results->ByNameOrMobile($data['keyword']);
        }

        return PatientResource::collection($results->latest()->get())->additional([
            "success" => true,
        ]);
    }

    public function show(Request $request, User $user): JsonResource
    {
        abort_if(!$user->isPatient(), 403);

        return apiJsonResource($user, PatientResource::class, true);
    }

    public function destroy(Request $request, User $user): JsonResource
    {
        abort_if(!$user->isPatient(), 403);

        return apiJsonResource([], null, $user->delete());
    }

    public function store(Request $request): JsonResource
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['required', 'numeric', 'unique:users,mobile'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);
        if ( isset($data['password']) ) {
            $data['password'] = Hash::make($data['password']);
        }
        $user = User::create($data);
        $user->assignRole(IRoleConst::PATIENT_ROLE);
        return apiJsonResource($user, PatientResource::class, true);
    }

    public function update(Request $request, User $user): JsonResource
    {
        abort_if(!$user->isPatient(), 403);

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile' => ['nullable', 'numeric', 'unique:users,mobile,' . $user->id],
            'password' => ['nullable', 'string', 'min:4', 'confirmed'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);
        if ( !empty($data) ) {
            if ( isset($data['password']) ) {
                $data['password'] = Hash::make($data['password']);
            }
            $user->update($data);
        }

        return apiJsonResource($user, PatientResource::class, true);
    }
}
