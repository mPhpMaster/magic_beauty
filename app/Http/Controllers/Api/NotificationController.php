<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(): JsonResource
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return NotificationResource::collection($user->notifications()->latest()->get())->additional([
            "success" => true,
        ]);
    }

    public function unread_count(Request $request): JsonResource
    {
        return apiJsonResource([
            'count' => $request->user()->unreadNotifications()->count(),
        ],null,true);
    }

    public function mark_as_read(\Illuminate\Notifications\DatabaseNotification $notification): JsonResource
    {
        $notification->markAsRead();
        return apiJsonResource($notification,NotificationResource::class,true);
    }

    public function destroy(\Illuminate\Notifications\DatabaseNotification $notification): JsonResource
    {
        return apiJsonResource([],null,$notification->delete());
    }
}
