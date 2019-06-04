<?php

namespace App\Models\Dashboard;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Printer
 * @package App\Models\Dashboard
 * @version May 25, 2019, 10:58 pm UTC
 *
 * @property string name
 * @property string address
 * @property integer status
 */
class Printer extends Model
{
    use SoftDeletes;

    public $table = 'printers';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'address',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'address' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'address' => 'required'
    ];

    
}
