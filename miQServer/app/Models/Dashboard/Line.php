<?php

namespace App\Models\Dashboard;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Line
 * @package App\Models\Dashboard
 * @version January 27, 2019, 8:18 am UTC
 *
 * @property string name
 * @property string color
 * @property integer priority
 */
class Line extends Model
{
    use SoftDeletes;

    public $table = 'lines';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'color',
        'priority',
        'company_id',
        'tags'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'color' => 'string',
        'priority' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'color' => 'required',
        'priority' => 'required'
    ];

    /**
     * @return mixed
     */
    public function lineDesks(){
        return $this->hasMany('App\Models\Dashboard\LineDesk');
    }

    /**
     * @return mixed
     */
    public function lineQueues(){
        return $this->hasMany('App\Models\Dashboard\QueueLine');
    }
    
}
