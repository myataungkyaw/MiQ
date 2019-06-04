<?php

namespace App\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class QueueCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class QueueCriteriaCriteria implements CriteriaInterface
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $name = $this->request->get('name');
        $company_id = $this->request->get('company_id');
        $line_id = $this->request->get('line_id');
        $queue_id = $this->request->get('queue_id');

        $model = $model->with('company')->with('queueLines');

        if ($name){
            $model = $model->where(function ($query) use ($name) {
                $query->where("name", 'like', '%'.$name.'%')
                         ->orWhere('phone', '=', $name)
                         ->orWhere('queue_number', '=', $name);
            });
        }
            

        if ($company_id)
            $model = $model->where('company_id', $company_id);

        if ($queue_id)
            $model = $model->where('id', $queue_id);

//        if ($line_id){
//            $model = $model->with( ['queueLines' => function($query) use ($line_id) {
//                $query->where('line_id', $line_id);
//            }]);

        if ($line_id){
            $model = $model->whereHas('queueLines', function ($query) use ($line_id) {
                $query->where('line_id', $line_id)->where('status', 0);
            });
        }

        return $model->today();
    }
}
