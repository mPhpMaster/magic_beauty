<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class FavoriteController
 *
 * @package App\Http\Controllers\Api
 */
class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResource
    {
        abort_if(!auth()->check(), 403);

        return FavoriteResource::collection(auth()->user()->favorites)->additional([
            "success" => true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function add_product(Request $request): JsonResource
    {
        abort_if(!auth()->check(), 403);

        $data = $request->validate([
            "product_id" => ['required', 'integer', 'exists:products,id'],
        ]);

        auth()->user()->favorites()->firstOrCreate($data);

        return FavoriteResource::collection(auth()->user()->favorites)->additional([
            "success" => true,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function remove_product(Request $request): JsonResource
    {
        abort_if(!auth()->check(), 403);

        $data = $request->validate([
            "product_id" => ['required', 'integer', 'exists:products,id'],
        ]);

        $deleted = auth()->user()->favorites()->where('product_id', $data['product_id'])->delete();

        return FavoriteResource::collection(auth()->user()->favorites)->additional([
            "success" => (bool)$deleted,
        ]);
    }
}
