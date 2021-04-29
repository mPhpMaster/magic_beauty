<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrescriptionResource;
use App\Interfaces\IRoleConst;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class PrescriptionController extends Controller
{
    public static function createPrescription(array $data, array $products): JsonResource
    {
        $model = Prescription::create($data)->assignProducts($products);
        return apiJsonResource($model, PrescriptionResource::class, true);
    }

    public function index(Request $request): JsonResource
    {
        $prescriptions = Prescription::query();
        if ( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->latest()->get());
    }

    public function patient_index(Request $request): JsonResource
    {
        $prescriptions = Prescription::byPatient($request->user()->id);
        if ( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->latest()->get());
    }

    public function pharmacist_index(Request $request): JsonResource
    {
        $prescriptions = Prescription::byPharmacist($request->user()->id);
        if ( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
        return PrescriptionResource::collection($prescriptions->latest()->get());
    }

    public function doctor_index(Request $request): JsonResource
    {
        $prescriptions = Prescription::byDoctor($request->user()->id);
        if ( $status = $request->get('status') ) {
            $prescriptions->byStatus(Prescription::getStatusId($status)->first());
        }
//        dd(/*$prescriptions->get(),*/ $request->user(),$request->user()->id);
        return PrescriptionResource::collection($prescriptions->latest()->get());
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
            'products.*.qty' => ['required', 'numeric', 'min:1'],
        ]);
        $data['doctor_id'] = $request->user()->id;
        $products = array_pull($data, 'products');

        return static::createPrescription($data, $products);
    }

    public function auto_create_patient(Request $request): JsonResource
    {
        $data = $request->validate([
            'pharmacist_id' => ['required', 'integer', 'exists:users,id'],
            'patient_name' => ['required', 'string'],
            'patient_mobile' => ['required', 'numeric'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:' . Prescription::getStatusId()->implode(',')],
            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.qty' => ['required', 'numeric', 'min:1'],
        ]);
        $data['doctor_id'] = $request->user()->id;
        $products = array_pull($data, 'products');

        $patient_name = array_pull($data, 'patient_name');
        $patient_mobile = array_pull($data, 'patient_mobile');
        if (
            ($patient = User::onlyPatients()->byActive()->byMobile($patient_mobile)->first()) ||
            ($patient = User::onlyPatients()->byActive()->byName($patient_name)->first())
        ) {
            $data['patient_id'] = $patient->id;
        } else {
            $patient = User::create([
                'name' => $patient_name,
                'email' => snake_case($patient_name) . "@" . snake_case($patient_name) . ".com",
                'mobile' => $patient_mobile,
                'password' => Hash::make($patient_mobile),
                'created_by' => $data['doctor_id'],
            ]);
            $patient->assignRole(IRoleConst::PATIENT_ROLE);
            $data['patient_id'] = $patient->id;
        }

        return static::createPrescription($data, $products);
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
            'products.*.qty' => ['nullable', 'numeric', 'min:1'],
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
