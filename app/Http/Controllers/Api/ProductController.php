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
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
//            'branch_id' => ['nullable', 'integer', 'exists:branches,id'],
            'status' => ['nullable', 'string', 'in:' . Product::getStatusId()->implode(',')],
        ]);

        $model = Product::query();
        if ( $category_id = $request->get('category_id') ) {
            $model->ByCategory($category_id);
        }
//        if ( $branch_id = $request->get('branch_id') ) {
//            $model->byBranch($branch_id);
//        }
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
            "name_en" => ['required', 'string', 'unique:products,name_en'],
            "name_ar" => ['required', 'string', 'unique:products,name_ar'],
            "description" => ['nullable', 'string', 'max:255'],
            "price" => ['required', 'numeric', 'min:0'],
            "need_prescription" => ['nullable', 'numeric', 'in:0,1'],
            "status" => ['nullable', 'string', 'in:' . Product::getStatusId()->implode(',')],
            'image' => ['nullable'],

            "branch_id" => ['nullable', 'string'],
            "qty" => ['required_with:branch_id', 'string'],
        ]);

        if ( isset($data['image']) ) {
            array_pull($data, 'image');
        }

        $branches = [];
        $qtys = [];
        if ( isset($data['branch_id']) || isset($data['qty']) ) {
            $branches = explode(",", array_pull($data, 'branch_id', ""));
            $qtys = explode(",", array_pull($data, 'qty', ""));
        }

        $model = Product::create($data);

        if ( $request->hasFile('image') ) {
            $model->addImage($request->file('image'));
        }

        foreach (array_filter($branches) as $key => $branch_id) {
            $model->updateQty($branch_id, (double)($qtys[ $key ] ?? 0));
        }

        return apiJsonResource($model, ProductResource::class, true);
    }

    public function update(Request $request, Product $model): JsonResource
    {
        abort_if(auth()->user() && !auth()->user()->isPharmacist() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            "category_id" => ['required', 'integer', 'exists:categories,id'],
            "name_en" => ['required', 'string', 'unique:products,name_en,' . $model->id],
            "name_ar" => ['required', 'string', 'unique:products,name_ar,' . $model->id],
            "description" => ['nullable', 'string', 'max:255'],
            "price" => ['required', 'numeric', 'min:0'],
            "need_prescription" => ['nullable', 'numeric', 'in:0,1'],
            "status" => ['nullable', 'string', 'in:' . Product::getStatusId()->implode(',')],
            'image' => ['nullable'],

            "branch_id" => ['nullable', 'string'],
            "qty" => ['required_with:branch_id', 'string'],
        ]);

        if ( !empty($data) ) {
            if ( isset($data['image']) ) {
                array_pull($data, 'image');
            }

            $branches = [];
            $qtys = [];
            if ( isset($data['branch_id']) || isset($data['qty']) ) {
                $branches = explode(",", array_pull($data, 'branch_id', ""));
                $qtys = explode(",", array_pull($data, 'qty', ""));
            }

            $model->update($data);

            if ( $request->hasFile('image') ) {
                $model->addImage($request->file('image'));
            }

            foreach (array_filter($branches) as $key => $branch_id) {
                $model->updateQty($branch_id, (double)($qtys[ $key ] ?? 0));
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
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);
        $results = Product::byActive();
        if ( $data['keyword'] ) {
            $results->ByNames($data['keyword']);
//                ->where(function ($q) use ($data) {
//                    $q->where('name', 'like', "%{$data['keyword']}%");
//
////                if ( $mobile = parseMobile($data['keyword']) ) {
////                    $q->orWhere('mobile', 'like', "%{$mobile}%");
////                }
//                });
        }

        if ( $category_id = $request->get('category_id') ) {
            $results->ByCategory($category_id);
        }

        if ( isset($data['branch_id']) ) {
            $results = $results->byBranch($data['branch_id']);
        }

        return ProductResource::collection($results->latest()->get())->additional([
            "success" => true,
        ]);
    }

//    public function change_qty(Request $request, Product $product): JsonResource
//    {
//        $data = $request->validate([
//            'qty' => ['required', 'numeric'],
//        ]);
//        $updated = $product->update($data);
//        return ProductResource::make($product->refresh())->additional([
//            "success" => $updated,
//        ]);
//    }

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
