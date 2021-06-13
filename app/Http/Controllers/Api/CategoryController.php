<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $data = $request->validate([
            'status' => ['nullable', 'string', 'in:' . Category::getStatusId()->implode(',')],
            'category_id' => ['nullable', 'int'],
        ]);

        $model = Category::byCategory($request->get('category_id',0));
        if ( $status = $request->get('status') ) {
            $model->byStatus(Category::getStatusId($status)->first());
        }
//        if ( $category_id = $request->get('category_id') ) {
//            $model->byCategory($category_id);
//        }

        return CategoryResource::collection($model->latest()->get());
    }

    public function show(Request $request, Category $model): JsonResource
    {
        return apiJsonResource($model, CategoryResource::class, true);
    }

    public function store(Request $request): JsonResource
    {
        abort_if(auth()->user() && !auth()->user()->isPharmacist() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            "category_id" => ['nullable', 'integer'],
            "name" => ['required', 'string', 'unique:categories,name'],
            "description" => ['nullable', 'string', 'max:255'],
            "status" => ['nullable', 'string', 'in:' . Category::getStatusId()->implode(',')],
            'image' => ['nullable'],
        ]);

        if ( isset($data['image']) ) {
            array_pull($data, 'image');
        }

        $model = Category::create($data);

        if ( $request->hasFile('image') ) {
            $model->addImage($request->file('image'));
        }

        return apiJsonResource($model, CategoryResource::class, true);
    }

    public function update(Request $request, Category $model): JsonResource
    {
        abort_if(auth()->user() && !auth()->user()->isPharmacist() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            "category_id" => ['nullable', 'integer'],
            "name" => ['required', 'string', 'unique:categories,name,' . $model->id],
            "description" => ['nullable', 'string', 'max:255'],
            "status" => ['nullable', 'string', 'in:' . Category::getStatusId()->implode(',')],
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

        return apiJsonResource($model, CategoryResource::class, true);
    }

    public function destroy(Request $request, Category $model): JsonResource
    {
        return apiJsonResource([], null, $model->delete());
    }

    public function search_for_category(Request $request): JsonResource
    {
        $data = $request->validate([
            'keyword' => ['nullable', 'string'],
        ]);
        $results = Category::byActive();
        if ( $data['keyword'] ) {
            $results = $results
                ->where(function ($q) use ($data) {
                    $q->where('name', 'like', "%{$data['keyword']}%");
                });
        }

        return CategoryResource::collection($results->latest()->get())->additional([
            "success" => true,
        ]);
    }
}
