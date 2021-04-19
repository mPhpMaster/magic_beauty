<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductController extends Controller
{
    public function show_ten_products(Request $request): JsonResource
    {
        return ProductResource::collection(Product::byActive()->limit(10)->get())->additional([
            "success" => true,
        ]);
    }

    public function index(Request $request): JsonResource
    {
        $model = Product::query();
        if ( $status = $request->get('status') ) {
            $model->byStatus(Product::getStatusId($status)->first());
        }
        return ProductResource::collection($model->get());
    }

    public function show(Request $request, Product $model): JsonResource
    {
        return apiJsonResource($model, ProductResource::class, true);
    }

    public function destroy(Request $request, Product $model): JsonResource
    {
        return apiJsonResource([], null, $model->delete());
    }

    public function search_for_product(Request $request): JsonResource
    {
        $data = $request->validate([
            'keyword' => ['required'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
        ]);

        $results = Product::byActive()
            ->where(function ($q) use($data) {
                $q->where('name', 'like', "%{$data['keyword']}%");

//                if ( $mobile = parseMobile($data['keyword']) ) {
//                    $q->orWhere('mobile', 'like', "%{$mobile}%");
//                }
            });

        if( isset($data['branch_id']) ) {
            $results = $results->byBranch($data['branch_id']);
        }

        return ProductResource::collection($results->get())->additional([
            "success" => true,
        ]);
    }
}
