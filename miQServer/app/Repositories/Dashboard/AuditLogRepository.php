<?php

namespace App\Repositories\Dashboard;

use App\Models\Dashboard\AuditLog;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AuditLogRepository
 * @package App\Repositories\Dashboard
 * @version January 26, 2019, 5:30 am UTC
 *
 * @method AuditLog findWithoutFail($id, $columns = ['*'])
 * @method AuditLog find($id, $columns = ['*'])
 * @method AuditLog first($columns = ['*'])
*/
class AuditLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'category',
        'user_id',
        'action'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AuditLog::class;
    }
}
