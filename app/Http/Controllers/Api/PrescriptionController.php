<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PrescriptionResource;
use App\Interfaces\IRoleConst;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class PrescriptionController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $prescriptions = Prescription::query();
        if( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->get());
    }

    public function patient_index(Request $request): JsonResource
    {
        $prescriptions = Prescription::byPatient($request->user()->id);
        if( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->get());
    }

    public function pharmacist_index(Request $request): JsonResource
    {
        $prescriptions = Prescription::byPharmacist($request->user()->id);
        if( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->get());
    }

    public function doctor_index(Request $request): JsonResource
    {
        $prescriptions = Prescription::byDoctor($request->user()->id);
        if( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->get());
    }

    public function show(Request $request, Prescription $model): JsonResource
    {
        abort_if(!$model->isBelongsToMe() && !auth()->user()->isSupport(), 403);

        return apiJsonResource($model, PrescriptionResource::class, true);
    }

    public function destroy(Request $request, Prescription $user): JsonResource
    {
        abort_if(!$user->isBelongsToMe() && !auth()->user()->isSupport(), 403);

        return apiJsonResource([], null, $user->delete());
    }

    public function store(Request $request): JsonResource
    {
        $data = $request->validate([
            'pharmacist_id' => ['required', 'integer', 'exists:users,id'],
            'patient_id' => ['required', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:' . Prescription::getStatusId()->implode(',')],
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.qty' => ['required', 'numeric'],
        ]);
        $products = array_pull($data, 'products');

        $data['doctor_id'] = $request->user()->id;
        $model = Prescription::create($data);
        $model->assignProducts($products);
        return apiJsonResource($model, PrescriptionResource::class, true);
    }

    public function update(Request $request, Prescription $model): JsonResource
    {
        abort_if(!$model->isBelongsToMe() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            'pharmacist_id' => ['nullable', 'integer', 'exists:users,id'],
            'patient_id' => ['nullable', 'integer', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:' . Prescription::getStatusId()->implode(',')],
            'products' => ['nullable', 'array'],
            'products.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'products.*.qty' => ['nullable', 'numeric'],
        ]);
        $products = array_pull($data, 'products');

        if ( !empty($products) ) {
            $model->assignProducts($products);
        }

        if ( !empty($data) ) {
            $model->update($data);
        }

        return apiJsonResource($model, PrescriptionResource::class, true);
    }

    public function cancel(Request $request, Prescription $model): JsonResource
    {
        abort_if(!$model->isBelongsToMe() && !auth()->user()->isSupport(), 403);
        $status = $model->setAsCanceled();
        return apiJsonResource($model->refresh(), PrescriptionResource::class, $status);
    }

    public function finish(Request $request, Prescription $model): JsonResource
    {
        abort_if(!$model->isBelongsToMe() && !auth()->user()->isSupport(), 403);
        $status = $model->setAsFinished();
        return apiJsonResource($model->refresh(), PrescriptionResource::class, $status);
    }
}
