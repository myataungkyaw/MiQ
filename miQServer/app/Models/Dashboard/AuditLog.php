<?php

namespace App\Models\Dashboard;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AuditLog
 * @package App\Models\Dashboard
 * @version January 26, 2019, 5:30 am UTC
 *
 * @property string category
 * @property integer user_id
 * @property string action
 */
class AuditLog extends Model
{
    use SoftDeletes;

    public $table = 'audit_logs';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'category',
        'user_id',
        'action'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'category' => 'string',
        'user_id' => 'integer',
        'action' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'category' => 'required',
        'user_id' => 'required',
        'action' => 'required'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Dashboard\User');
    }
    
}
