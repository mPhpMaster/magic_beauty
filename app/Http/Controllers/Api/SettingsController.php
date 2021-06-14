<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SettingsController
 *
 * @package App\Http\Controllers\Api
 */
class SettingsController extends Controller
{
    public function social_media(Request $request): JsonResource
    {
        return JsonResource::make(config('settings.social_media'))->additional([
            'success' => true
        ]);
    }

    public function support_email(Request $request): JsonResource
    {
        return JsonResource::make(config('settings.support_email'))->additional([
            'success' => true
        ]);
    }

}
