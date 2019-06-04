<?php

namespace App\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AuditCriteria.
 *
 * @package namespace App\Criteria;
 */
class AuditCriteria implements CriteriaInterface
{
    protected $request;

    /**
     * AuditCriteria constructor.
     * @param Request $request
     */
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
        $user_id = $this->request->get('user_id');
        $category = $this->request->get('category');

        if ($user_id && $user_id !="0")
            $model->where("user_id", $user_id);

        if ($category && $category != "all")
            $model->where('category', 'like', '%'.$category.'%');

        return $model;
    }
}
