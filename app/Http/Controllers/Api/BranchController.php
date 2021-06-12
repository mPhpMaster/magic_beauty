<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class BranchController
 *
 * @package App\Http\Controllers\Api
 */
class BranchController extends Controller
{
    public function index(Request $request): JsonResource
    {
        abort_if( auth()->user() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $branches = Branch::query();
        if ( $status = $request->get('status') ) {
            $branches->byStatus(Branch::getStatusId($status)->first());
        }
        return BranchResource::collection($branches->latest()->get());
    }

    public function search_for_branch(Request $request): JsonResource
    {
        $data = $request->validate([
            'keyword' => ['nullable', 'string'],
        ]);

        $results = Branch::ByActive('global');
        if ( $data['keyword'] ) {
            $results = $results->ByName($data['keyword']);
        }

        return BranchResource::collection($results->latest()->get())->additional([
            "success" => true,
        ]);
    }

    public function show(Request $request, Branch $model): JsonResource
    {
        abort_if( auth()->user() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        return apiJsonResource($model, BranchResource::class, true);
    }

    public function destroy(Request $request, Branch $model): JsonResource
    {
        abort_if( auth()->user() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        return apiJsonResource([], null, $model->delete());
    }

    public function store(Request $request): JsonResource
    {
        abort_if( auth()->user() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        $branch = Branch::create($data);

        return apiJsonResource($branch, BranchResource::class, true);
    }

    public function update(Request $request, Branch $model): JsonResource
    {
        abort_if( auth()->user() && !auth()->user()->isAdministrator() && !auth()->user()->isSupport(), 403);

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'in:active,inactive'],
        ]);

        if ( !empty($data) ) {
            $model->update($data);
        }

        return apiJsonResource($model, BranchResource::class, true);
    }
}
