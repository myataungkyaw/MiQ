<?php

namespace App\Repositories\Dashboard;

use App\Models\Dashboard\Company;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CompanyRepository
 * @package App\Repositories\Dashboard
 * @version February 17, 2019, 8:05 am UTC
 *
 * @method Company findWithoutFail($id, $columns = ['*'])
 * @method Company find($id, $columns = ['*'])
 * @method Company first($columns = ['*'])
*/
class CompanyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'address',
        'background_image',
        'logo',
        'log_retention_period',
        'queue_prefix',
        'note',
        'third_party_integration',
        'license_key',
        'last_sync'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Company::class;
    }
}
