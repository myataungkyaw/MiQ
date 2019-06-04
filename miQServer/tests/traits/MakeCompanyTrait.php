<?php

use Faker\Factory as Faker;
use App\Models\Dashboard\Company;
use App\Repositories\Dashboard\CompanyRepository;

trait MakeCompanyTrait
{
    /**
     * Create fake instance of Company and save it in database
     *
     * @param array $companyFields
     * @return Company
     */
    public function makeCompany($companyFields = [])
    {
        /** @var CompanyRepository $companyRepo */
        $companyRepo = App::make(CompanyRepository::class);
        $theme = $this->fakeCompanyData($companyFields);
        return $companyRepo->create($theme);
    }

    /**
     * Get fake instance of Company
     *
     * @param array $companyFields
     * @return Company
     */
    public function fakeCompany($companyFields = [])
    {
        return new Company($this->fakeCompanyData($companyFields));
    }

    /**
     * Get fake data of Company
     *
     * @param array $postFields
     * @return array
     */
    public function fakeCompanyData($companyFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'address' => $fake->text,
            'background_image' => $fake->word,
            'logo' => $fake->word,
            'log_retention_period' => $fake->randomDigitNotNull,
            'queue_prefix' => $fake->word,
            'note' => $fake->text,
            'third_party_integration' => $fake->randomDigitNotNull,
            'license_key' => $fake->word,
            'last_sync' => $fake->date('Y-m-d H:i:s'),
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $companyFields);
    }
}
