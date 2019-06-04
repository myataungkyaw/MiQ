<?php

namespace App\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class QueueLineCriteria.
 *
 * @package namespace App\Criteria;
 */
class QueueLineCriteria implements CriteriaInterface
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
        $line_id = $this->request->get('line_id');
        $model = $model->with('queue')->with('line');
        
        if ($line_id){
            $model = $model->where('line_id', $line_id);
        }

        $current = $this->request->get("current");
        if($current==1){
            $model = $model->where('status', 1);
            if($this->request->get('desk_id')){
            $model = $model->where('line_desk_id', $this->request->get('desk_id'));
            }
        }else{
           $model = $model->pending();
        }
           
        return $model->today();
    }
}
