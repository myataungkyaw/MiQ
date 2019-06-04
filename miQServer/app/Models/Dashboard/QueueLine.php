<?php

namespace App\Models\Dashboard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueueLine extends Model
{
    const PENDING = 0;
    const SERVING = 1;
    const DONE = 2;
    const NO_SHOW = 3;

    use SoftDeletes;

    public $table = 'queue_lines';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'queue_id',
        'line_id',
        'line_desk_id',
        'position',
        'status',
        'on_hold',
        'started_at',
        'finished_at',
        'called_at',
        'number_of_returnq'
    ];

    public function queue()
    {
        return $this->belongsTo('App\Models\Dashboard\Queue');
    }

    public function line()
    {
        return $this->belongsTo('App\Models\Dashboard\Line');
    }

    public function scopePending($query)
    {
        return $query->where("status", QueueLine::PENDING);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
    
}
