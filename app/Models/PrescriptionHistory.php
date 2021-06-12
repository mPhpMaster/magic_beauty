<?php

namespace App\Models;

use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionHistory extends Model
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
        "prescription_id",
        "doctor_id",
        "pharmacist_id",
        "patient_id",
        "notes",
        "status",
    ];

    /**
     * @param int|\App\Models\Prescription $id
     *
     * @return \App\Models\PrescriptionHistory|\Illuminate\Database\Eloquent\Model
     */
    public static function createFromPrescription($id)
    {
        $prescription = $id instanceof Prescription ? $id : Prescription::findOrFail($id);
        $prescription_history = static::make($prescription->attributes);
        $prescription_history->prescription_id = $prescription->id;
        $prescription_history->save();
        $prescription_history->assignProducts($prescription->products->map(fn($product) => $product->pivot->only(['product_id', 'qty']))->toArray());

        return $prescription_history;
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
        return $this->belongsToMany(Product::class, 'product_prescription_history')->withPivot('qty');
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id');
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

    public function scopeByPrescription(\Illuminate\Database\Eloquent\Builder $query, int $id): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('prescription_id', $id);
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

}
