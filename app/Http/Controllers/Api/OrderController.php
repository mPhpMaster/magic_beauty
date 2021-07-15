<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderController
 *
 * @package App\Http\Controllers\Api
 */
class OrderController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $orders = Order::query();

        if ( $branch_id = $request->get('branch_id') ) {
            $orders->byBranch($branch_id);
        }

        if ( $status = $request->get('status') ) {
            $orders->byStatus(Order::getStatusId($status)->first());
        }
        return OrderResource::collection($orders->latest()->get());
    }

    public function success_index(Request $request): JsonResource
    {
        return OrderResource::collection(Order::BySuccess()->latest()->get());
    }

    public function failed_index(Request $request): JsonResource
    {
        return OrderResource::collection(Order::ByFailed()->latest()->get());
    }

    public function canceled_index(Request $request): JsonResource
    {
        return OrderResource::collection(Order::ByCanceled()->latest()->get());
    }

    public function finished_index(Request $request): JsonResource
    {
        return OrderResource::collection(Order::ByFinished()->latest()->get());
    }

    public function pending_index(Request $request): JsonResource
    {
        return OrderResource::collection(Order::ByPending()->latest()->get());
    }

    public function show(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);

        return apiJsonResource($model, OrderResource::class, true);
    }

    public function destroy(Request $request, Order $user): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);

        return apiJsonResource([], null, $user->delete());
    }

    public function store(Request $request): JsonResource
    {
        $data = $request->validate([
            'pay_type_id' => ['required', 'integer', 'exists:pay_types,id'],
            'branch_id' => ['required', 'integer', 'exists:branches,id'],
//            'vat' => ['nullable', 'numeric', 'min:0'],
//            'vat_percentage' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:' . Order::getStatusId()->implode(',')],

            'products' => ['required', 'array'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.qty' => ['required', 'numeric', 'min:1'],
            'products.*.price' => ['required', 'numeric', 'min:1'],
//            'products.*.vat' => ['nullable', 'numeric', 'min:1'],
//            'products.*.vat_percentage' => ['nullable', 'numeric', 'min:1'],
            'products.*.note' => ['nullable', 'string'],
        ]);

        $products = toCollect(array_pull($data, 'products'))->map(function ($product) use (&$data) {
            $product['sub_total'] = (double)$product['qty'] * (double)$product['price'];
            $product['total'] = (double)$product['sub_total'];
            $data['sub_total'] = (double)($data['sub_total'] ?? 0) + (double)$product['total'];
            return $product;
        })->toArray();
        $data['total'] = (double)$data['sub_total'];

        return static::createOrder($data, $products);
    }

    public static function createOrder(array $data, array $products): JsonResource
    {
        $model = Order::create($data)->assignProducts($products);
        return apiJsonResource($model, OrderResource::class, true);
    }

    public function update(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);

        $data = $request->validate([
//            'pay_type_id' => ['required', 'integer', 'exists:pay_types,id'],
//            'branch_id' => ['required', 'integer', 'exists:branches,id'],
//            'vat' => ['nullable', 'numeric', 'min:0'],
//            'vat_percentage' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:' . Order::getStatusId()->implode(',')],

//            'products' => ['required', 'array'],
//            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
//            'products.*.qty' => ['required', 'numeric', 'min:1'],
//            'products.*.price' => ['required', 'numeric', 'min:1'],
//            'products.*.vat' => ['nullable', 'numeric', 'min:1'],
//            'products.*.vat_percentage' => ['nullable', 'numeric', 'min:1'],
//            'products.*.note' => ['nullable', 'string'],
        ]);
//        $products = array_pull($data, 'products');

//        if ( !empty($products) ) {
//            $model->assignProducts($products);
//        }

        if ( !empty($data) ) {
            $model->update($data);
        }

        return apiJsonResource($model, OrderResource::class, true);
    }

    public function success(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);
        $status = $model->setAsSuccess();
        return apiJsonResource($model->refresh(), OrderResource::class, $status);
    }

    public function failed(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);
        $status = $model->setAsFailed();
        return apiJsonResource($model->refresh(), OrderResource::class, $status);
    }

    public function pending(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);
        $status = $model->setAsPending();
        return apiJsonResource($model->refresh(), OrderResource::class, $status);
    }

    public function finished(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);
        $status = $model->setAsFinished();
        return apiJsonResource($model->refresh(), OrderResource::class, $status);
    }

    public function canceled(Request $request, Order $model): JsonResource
    {
//        abort_if(!auth()->user()->isSupport(), 403);
        $status = $model->setAsCanceled();
        return apiJsonResource($model->refresh(), OrderResource::class, $status);
    }
}
