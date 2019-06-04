<?php

use Faker\Factory as Faker;
use App\Models\Dashboard\AuditLog;
use App\Repositories\Dashboard\AuditLogRepository;

trait MakeAuditLogTrait
{
    /**
     * Create fake instance of AuditLog and save it in database
     *
     * @param array $auditLogFields
     * @return AuditLog
     */
    public function makeAuditLog($auditLogFields = [])
    {
        /** @var AuditLogRepository $auditLogRepo */
        $auditLogRepo = App::make(AuditLogRepository::class);
        $theme = $this->fakeAuditLogData($auditLogFields);
        return $auditLogRepo->create($theme);
    }

    /**
     * Get fake instance of AuditLog
     *
     * @param array $auditLogFields
     * @return AuditLog
     */
    public function fakeAuditLog($auditLogFields = [])
    {
        return new AuditLog($this->fakeAuditLogData($auditLogFields));
    }

    /**
     * Get fake data of AuditLog
     *
     * @param array $postFields
     * @return array
     */
    public function fakeAuditLogData($auditLogFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'category' => $fake->word,
            'user_id' => $fake->randomDigitNotNull,
            'action' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $auditLogFields);
    }
}
