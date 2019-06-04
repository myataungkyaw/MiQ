<?php

namespace App\Models\Dashboard;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 * @package App\Models\Dashboard
 * @version February 17, 2019, 8:05 am UTC
 *
 * @property string name
 * @property string address
 * @property string background_image
 * @property string logo
 * @property integer log_retention_period
 * @property string queue_prefix
 * @property string note
 * @property integer third_party_integration
 * @property string license_key
 * @property string|\Carbon\Carbon last_sync
 */
class Company extends Model
{
    use SoftDeletes;

    public $table = 'companies';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'address',
        'background_image',
        'logo',
        'log_retention_period',
        'queue_prefix',
        'note',
        'third_party_integration',
        'license_key',
        'last_sync',
        'scrolling_text',
        'notification_sound'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'address' => 'string',
        'background_image' => 'string',
        'logo' => 'string',
        'log_retention_period' => 'integer',
        'queue_prefix' => 'string',
        'note' => 'string',
        'third_party_integration' => 'integer',
        'license_key' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'name' => 'required',
        // 'address' => 'required',
        // 'background_image' => 'required',
        // 'logo' => 'required',
        // 'log_retention_period' => 'required',
        // 'queue_prefix' => 'required',
        // 'third_party_integration' => 'required'
    ];

    
}
