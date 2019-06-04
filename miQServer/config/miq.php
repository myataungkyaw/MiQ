<?php

return [
    'roles'=>[
        '1'=>"Super Admin",
        '2'=>"Company Admin",
        '3'=>"Company User"
        ],

    'logs_retention_peroid'=>[
        90 =>'3 month',
        180 =>'6 months',
        270 =>'9 month',
        365 =>'12 months',
    ],

    'status'=>[
        0 =>'Pending',
        1 =>'Serving',
        2 =>'Finished',
        3 =>'Cancel'
    ]
];
