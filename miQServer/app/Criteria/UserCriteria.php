<?php

namespace App\Criteria;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserCriteria.
 *
 * @package namespace App\Criteria;
 */
class UserCriteria implements CriteriaInterface
{
    protected $request;

    /**
     * UserCriteria constructor.
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
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $phone = $this->request->get('phone');

        if ($name)
            $model = $model->where("name", 'like', '%'.$name.'%');

        if ($email)
            $model = $model->where('email', 'like', '%'.$email.'%');

        if ($phone)
            $model = $model->where('phone', 'like', '%'.$phone.'%');

        return $model;
    }
}
