<?php

namespace App\Http\Controllers\API\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class MacroController extends AppBaseController
{
    public function getLogRetention (){
        $logRetentions = config('miq.logs_retention_peroid');
        return $this->sendResponse($logRetentions, 'msg');
    }

    public function getUserRole (){
        $userRoles = config('miq.roles');
        return $this->sendResponse($userRoles, 'msg');
    }

}
