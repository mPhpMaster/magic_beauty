<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Report\PrescriptionsResource;
use App\Http\Resources\Report\ProductQtyResource;
use App\Models\Branch;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ReportController
 *
 * @package App\Http\Controllers\Api
 */
class ReportController extends Controller
{
    public function product_qty(Request $request): JsonResource
    {
        $data = $request->validate([
            'branch_id' => ['nullable', 'array'],
            'branch_id.*' => ['nullable', 'integer', 'exists:branches,id'],
            'product_id' => ['nullable', 'array'],
            'product_id.*' => ['nullable', 'integer', 'exists:products,id'],
        ]);

        $model = Branch::query();
        if ( $branch_id = $request->get('branch_id', []) ) {
            $model = Branch::whereKey($branch_id);
        }

        if ( $product_id = $request->get('product_id') ) {
            $model->with('products', fn($q) => $q->whereIn('product_id', $product_id));
        } else {
            $model->with('products');
        }

        return ProductQtyResource::collection($model->get()->map->products->flatten()->sortDesc());
    }

    public function prescriptions(Request $request): JsonResource
    {
        $data = $request->validate([
            'branch_id' => ['nullable', 'array'],
            'branch_id.*' => ['nullable', 'integer', 'exists:branches,id'],
            'product_id' => ['nullable', 'array'],
            'product_id.*' => ['nullable', 'integer', 'exists:products,id'],
            'doctor_id' => ['nullable', 'integer', 'exists:users,id'],
            'pharmacist_id' => ['nullable', 'integer', 'exists:users,id'],
            'patient_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['nullable', 'array'],
            'status.*' => ['nullable', 'string', 'in:' . Prescription::getStatusId()->implode(',')],
        ]);

        $model = Prescription::query();
        $pharmacists = toCollect([]);
        if ( $branch_id = $request->get('branch_id', []) ) {
            $pharmacists = User::whereHas('branch',fn($q)=>$q->whereIn('id',$branch_id))->pluck('id');
        }

        if ( $product_id = $request->get('product_id', []) ) {
            $model->whereHas('products',fn($q)=>$q->whereIn('product_id',$product_id));
        }

        if ( $doctor_id = $request->get('doctor_id', []) ) {
            $model->ByDoctor($doctor_id);
        }

        if ( ($pharmacist_id = $request->get('pharmacist_id', [])) || $pharmacists->isNotEmpty() ) {
            $pharmacists = $pharmacists ?: toCollect([]);
            if($pharmacist_id) {
                $pharmacists = $pharmacists->add($pharmacist_id)->unique();
            }

            $model->ByPharmacist($pharmacists->toArray());
        }

        if ( $patient_id = $request->get('patient_id', []) ) {
            $model->ByPatient($patient_id);
        }

        if ( $status = $request->get('status', []) ) {
            $model->byStatus(toCollect($status)->map(fn($s)=>Prescription::getStatusId($s)->first())->toArray());
        }

        return PrescriptionsResource::collection($model->latest()->get());
    }
}
