<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LineDesk extends Model
{
    use SoftDeletes;

    public $table = 'line_desks';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'line_id',
        'name'
    ];
}
