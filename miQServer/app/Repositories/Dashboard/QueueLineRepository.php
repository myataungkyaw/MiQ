<?php

namespace App\Repositories\Dashboard;

use App\Models\Dashboard\QueueLine;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class QueueRepository
 * @package App\Repositories\Dashboard
 * @version February 17, 2019, 8:07 am UTC
 *
 * @method Queue findWithoutFail($id, $columns = ['*'])
 * @method Queue find($id, $columns = ['*'])
 * @method Queue first($columns = ['*'])
 */
class QueueLineRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'queue_id',
        'line_id',
        'position',
        'call_number',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return QueueLine::class;
    }
}
