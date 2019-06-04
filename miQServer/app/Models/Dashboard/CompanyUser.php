<?php

namespace App\models\dashboard;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    public $table = 'company_users';
    

    protected $dates = [];


    public $fillable = [
        'company_id',
        'user_id',
    
    ];
}
