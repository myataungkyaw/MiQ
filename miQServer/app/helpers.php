<?php 


if(!function_exists('audit_log')){
    function audit_log($cat, $action){
      return  App\Models\Dashboard\AuditLog::create([
            'category'=> $cat,
            'user_id'=> auth()->id(),
            'action'=> $action
        ]);
    }
}