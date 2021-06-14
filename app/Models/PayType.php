<?php

namespace App\Models;

use App\Traits\THasMultiName;
use App\Traits\THasScopeBy;
use App\Traits\THasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PayType
 *
 * @package App\Models
 */
class PayType extends Model
{
    /**
     * Status type
     */
    public const STATUS_TYPE = 'global';

    use HasFactory,
        THasStatus,
        THasScopeBy,
        THasMultiName;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name_en',
        'name_ar',
        'status',
    ];

}
