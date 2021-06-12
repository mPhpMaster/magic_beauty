<?php

namespace App\Http\Controllers\Api;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function show_ten_products(Request $request): JsonResource
    {
        return ProductResource::collection(Product::byActive()->limit(10)->latest()->get())->additional([
            "success" => true,
        ]);
    }

    public function index(Request $request): JsonResource
    {
        $data = $request->validate([
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'status' => ['nullable', 'string', 'in:' . Product::getStatusId()->implode(',')],
        ]);

        $model = Product::query();
        if ( $branch_id = $request->get('branch_id') ) {
            $model->byBranch($branch_id);
        }
        if ( $status = $request->get('status') ) {
            $model->byStatus(Product::getStatusId($status)->first());
        }
        return ProductResource::collection($model->latest()->get());
    }

    public function show(Request $request, Product $model): JsonResource
    {
        return apiJsonResource($model, ProductResource::class, true);
    }

    public function store(Request $request): JsonResource
    {
        abort_if(auth()->user() && !auth()->user()->isPharmacist() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            "category_id" => ['required', 'integer', 'exists:categories,id'],
            "branch_id" => ['required', 'integer', 'exists:branches,id'],
            "name" => ['required', 'string', 'unique:products,name'],
            "description" => ['nullable', 'string', 'max:255'],
            "price" => ['required', 'numeric', 'min:0'],
            "qty" => ['required', 'numeric', 'min:0'],
            "status" => ['nullable', 'string', 'in:' . Product::getStatusId()->implode(',')],
            'image' => ['nullable'],
        ]);

        if ( isset($data['image']) ) {
            array_pull($data, 'image');
        }

        $model = Product::create($data);

        if ( $request->hasFile('image') ) {
            $model->addImage($request->file('image'));
        }

        return apiJsonResource($model, ProductResource::class, true);
    }

    public function update(Request $request, Product $model): JsonResource
    {
        abort_if(auth()->user() && !auth()->user()->isPharmacist() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            "category_id" => ['required', 'integer', 'exists:categories,id'],
            "branch_id" => ['required', 'integer', 'exists:branches,id'],
            "name" => ['required', 'string', 'unique:products,name,' . $model->id],
            "description" => ['nullable', 'string', 'max:255'],
            "price" => ['required', 'numeric', 'min:0'],
            "qty" => ['required', 'numeric', 'min:0'],
            "status" => ['nullable', 'string', 'in:' . Product::getStatusId()->implode(',')],
            'image' => ['nullable'],
        ]);

        if ( !empty($data) ) {
            if ( isset($data['image']) ) {
                array_pull($data, 'image');
            }

            $model->update($data);

            if ( $request->hasFile('image') ) {
                $model->addImage($request->file('image'));
            }
        }

        return apiJsonResource($model, ProductResource::class, true);
    }

    public function destroy(Request $request, Product $model): JsonResource
    {
        return apiJsonResource([], null, $model->delete());
    }

    public function search_for_product(Request $request): JsonResource
    {
        $data = $request->validate([
            'keyword' => ['nullable', 'string'],
            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
        ]);
        $results = Product::byActive();
        if ( $data['keyword'] ) {
            $results = $results
                ->where(function ($q) use ($data) {
                    $q->where('name', 'like', "%{$data['keyword']}%");

//                if ( $mobile = parseMobile($data['keyword']) ) {
//                    $q->orWhere('mobile', 'like', "%{$mobile}%");
//                }
                });
        }

        if ( isset($data['branch_id']) ) {
            $results = $results->byBranch($data['branch_id']);
        }

        return ProductResource::collection($results->latest()->get())->additional([
            "success" => true,
        ]);
    }

    public function product_template_excel()
    {
        $path = '/storage/productsTemplate.xlsx';
        Excel::store(new ProductsExport(), $path);
        return url($path);

    }

    public function product_import_excel(Request $request)
    {
        $request->validate([
            'excel' => ['required'],
        ]);
        Excel::import(new ProductsImport($request->user()->id), request()->file('excel'));
    }
}
