<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\User;
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

    /**
     * Write code on Method
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource()
     */
    public function saveToken(Request $request)
    {
        return apiJsonResource([
            'message' => __("token saved successfully."),
        ],null,auth()->user()->update(['device_token'=>$request->token]));
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAA93owWYc:APA91bHCw3hYhFXG1ZACish9sNqKdFP4vl6nARn4XdgLISwkQvf2o9kXhmfETfotG1VLCAfBu13MB4ygOq2FX2NTm0kkjeZDZfmDv-VApFe-tzqY1qC0hegLOJCryK0UkYRwc5LHPtb9';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
        return $response;
    }
}
