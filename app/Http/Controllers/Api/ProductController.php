<?php

namespace App\Http\Controllers\Api;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Imports\ProductsImport;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
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

    public function product_template_excel(){
        $path = '/storage/productsTemplate.xlsx';
         Excel::store(new ProductsExport(),$path);
         return url($path);

    }


    public function product_import_excel(Request $request){
         $request->validate([
            'excel' => ['required'],
        ]);
        Excel::import(new ProductsImport($request->user()->id), request()->file('excel'));   
    }
}
