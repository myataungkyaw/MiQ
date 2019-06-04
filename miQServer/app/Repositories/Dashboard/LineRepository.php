<?php

namespace App\Repositories\Dashboard;

use App\Models\Dashboard\Line;
use App\Models\Dashboard\QueueLine;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class LineRepository
 * @package App\Repositories\Dashboard
 * @version January 27, 2019, 8:18 am UTC
 *
 * @method Line findWithoutFail($id, $columns = ['*'])
 * @method Line find($id, $columns = ['*'])
 * @method Line first($columns = ['*'])
*/
class LineRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'color',
        'priority'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Line::class;
    }

    public function getLineByCompany($company_id)
    {

        return Line::with('lineDesks')
       // ->with('lineQueues')
        ->with(['lineQueues' => function ($query) {
            $query->whereDate('queue_lines.created_at', today())
            ->where("queue_lines.status", QueueLine::PENDING);
        }])
        ->where('company_id', $company_id)->orderBy("priority","ASC")->get();
    }
}
