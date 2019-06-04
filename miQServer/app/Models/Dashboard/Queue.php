<?php

namespace App\Models\Dashboard;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Queue
 * @package App\Models\Dashboard
 * @version February 17, 2019, 8:07 am UTC
 *
 * @property integer company_id
 * @property string name
 * @property string phone
 * @property string third_party_code
 * @property integer status
 */
class Queue extends Model
{
    use SoftDeletes;

    public $table = 'queues';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'queue_number',
        'company_id',
        'name',
        'phone',
        'third_party_code',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_id' => 'integer',
        'name' => 'string',
        'phone' => 'string',
        'third_party_code' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'company_id' => 'required',
        'name' => 'required',
        'phone' => 'required'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Dashboard\Company');
    }

    public function queueLines()
    {
        return $this->belongsToMany('App\Models\Dashboard\Line', 'queue_lines', 'queue_id', 'line_id')->withPivot('id', 'position', 'call_number');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    
}
