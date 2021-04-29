<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NotificationResource
 *
 * @package App\Http\Resources
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var \Illuminate\Notifications\DatabaseNotification $model */
        $model = $this->resource;
        $user = $model->notifiable()->withDefault(User::make())->first();
        $data = $model->data;

        return [
            "id" => $model->id,
            "user" => $user ? $user->name : null,
            "title" => data_get($data, 'title'),
            "description" => data_get($data, 'description'),
            "prescription_id" => data_get($data, 'prescription_id'),
            "date" => $model->created_at->format("Y-m-d h:i a"),
            "is_read" => $model->read(),
            "read_at" => $model->read_at ? Carbon::parse($model->read_at)->format("Y-m-d h:i a") : null,
        ];
    }
}
