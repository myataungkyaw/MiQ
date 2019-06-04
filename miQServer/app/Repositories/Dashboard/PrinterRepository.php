<?php

namespace App\Repositories\Dashboard;

use App\Models\Dashboard\Printer;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PrinterRepository
 * @package App\Repositories\Dashboard
 * @version May 25, 2019, 10:58 pm UTC
 *
 * @method Printer findWithoutFail($id, $columns = ['*'])
 * @method Printer find($id, $columns = ['*'])
 * @method Printer first($columns = ['*'])
*/
class PrinterRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Printer::class;
    }
}
