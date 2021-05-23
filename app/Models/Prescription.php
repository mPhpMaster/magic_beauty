<?php

namespace App\Models;

use App\Notifications\PrescriptionCreated;
use App\Notifications\PrescriptionFinished;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'prescriptions';

    use HasFactory;
    use THasScopeBy;
    use THasStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        "doctor_id",
        "pharmacist_id",
        "patient_id",
        "notes",
        "status",
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Prescription $model) {
            if ( !$model->doctor_id ) {
                $model->doctor_id = auth()->id();
            }
            $model->status = static::getStatusId($model->status ?: 'pending')->first();
        });

        static::created(function (Prescription $model) {
            if ( $pharmacist = $model->pharmacist ) {
                $pharmacist->notify(new PrescriptionCreated($model,__("Prescription Created"),__("Prescription created please make action")));
            }
        });
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Model|array $ids
     * @param bool                                                                     $detaching
     *
     * @return $this
     */
    public function assignProducts($ids, $detaching = true)
    {
        // fix: duplicate when update products
        $this->products()->sync([]);
        $this->products()->sync($ids, $detaching);
        return $this;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_prescription')->withPivot('qty');
    }

    public function getDoctorNameAttribute()
    {
        return ($d = $this->doctor) ? $d->name : "";
    }

    public function getPharmacistNameAttribute()
    {
        return ($d = $this->pharmacist) ? $d->name : "";
    }

    public function getPatientNameAttribute()
    {
        return ($d = $this->patient) ? $d->name : "";
    }

    public function getPatientMobileAttribute()
    {
        return ($d = $this->patient) ? $d->mobile : "";
    }

    /**
     * @return bool
     */
    public function isBelongsToMe()
    {
        if ( !($user = auth()->user()) || !($user_id = $user->id) ) {
            return false;
        }

        return $this->doctor_id === $user_id ||
            $this->pharmacist_id === $user_id ||
            $this->patient_id === $user_id;
    }

    public function setAsPending(): bool
    {
        return $this->setStatus('pending')->save();
    }

    public function setAsCanceled(): bool
    {
        $result = $this->setStatus('canceled')->save();
        if($doctor = $this->doctor) {
            $doctor->notify(new PrescriptionFinished($this,__('Prescription canceled'), __('Prescription canceled')));
        }
        return $result;
    }

    public function setAsFinished(): bool
    {
        $result = $this->setStatus('finished')->save();
        if($doctor = $this->doctor) {
            $doctor->notify(new PrescriptionFinished($this,__('Prescription finished'), __('Prescription finished')));
        }
        return $result;
    }

    public function scopeByPending(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('pending', $type);
    }

    public function scopeByFinished(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('finished', $type);
    }

    public function scopeByCanceled(\Illuminate\Database\Eloquent\Builder $query, ?string $type = null): \Illuminate\Database\Eloquent\Builder
    {
        return $query->byStatus('canceled', $type);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|int[]                             $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDoctor(\Illuminate\Database\Eloquent\Builder $query, $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereIn('doctor_id', (array)$id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|int[]                             $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPharmacist(\Illuminate\Database\Eloquent\Builder $query, $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereIn('pharmacist_id', (array)$id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|int[]                             $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPatient(\Illuminate\Database\Eloquent\Builder $query, $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereIn('patient_id', (array)$id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|int[]                             $id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByAnyUser(\Illuminate\Database\Eloquent\Builder $query, $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereIn('doctor_id', (array)$id)
            ->orWhereIn('pharmacist_id', (array)$id)
            ->orWhereIn('patient_id', (array)$id);
    }


    /**
     * @param \App\Models\Prescription $prescription
     *
     * @return mixed
     */
    public function sendFirebase($title, $body)
    {
        $firebaseToken = $this->only(['device_token']);

        $SERVER_API_KEY = config('firebase.api_key');

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
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
        /** @noinspection CurlSslServerSpoofingInspection */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

//        dd($response);
        return $response;
    }

}
