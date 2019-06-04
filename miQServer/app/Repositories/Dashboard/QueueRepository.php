<?php

namespace App\Repositories\Dashboard;

use App\Models\Dashboard\Queue;
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
class QueueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'name',
        'phone',
        'third_party_code',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Queue::class;
    }
}
