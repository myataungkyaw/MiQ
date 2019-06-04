<?php

use App\Models\Dashboard\AuditLog;
use App\Repositories\Dashboard\AuditLogRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditLogRepositoryTest extends TestCase
{
    use MakeAuditLogTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var AuditLogRepository
     */
    protected $auditLogRepo;

    public function setUp()
    {
        parent::setUp();
        $this->auditLogRepo = App::make(AuditLogRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateAuditLog()
    {
        $auditLog = $this->fakeAuditLogData();
        $createdAuditLog = $this->auditLogRepo->create($auditLog);
        $createdAuditLog = $createdAuditLog->toArray();
        $this->assertArrayHasKey('id', $createdAuditLog);
        $this->assertNotNull($createdAuditLog['id'], 'Created AuditLog must have id specified');
        $this->assertNotNull(AuditLog::find($createdAuditLog['id']), 'AuditLog with given id must be in DB');
        $this->assertModelData($auditLog, $createdAuditLog);
    }

    /**
     * @test read
     */
    public function testReadAuditLog()
    {
        $auditLog = $this->makeAuditLog();
        $dbAuditLog = $this->auditLogRepo->find($auditLog->id);
        $dbAuditLog = $dbAuditLog->toArray();
        $this->assertModelData($auditLog->toArray(), $dbAuditLog);
    }

    /**
     * @test update
     */
    public function testUpdateAuditLog()
    {
        $auditLog = $this->makeAuditLog();
        $fakeAuditLog = $this->fakeAuditLogData();
        $updatedAuditLog = $this->auditLogRepo->update($fakeAuditLog, $auditLog->id);
        $this->assertModelData($fakeAuditLog, $updatedAuditLog->toArray());
        $dbAuditLog = $this->auditLogRepo->find($auditLog->id);
        $this->assertModelData($fakeAuditLog, $dbAuditLog->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteAuditLog()
    {
        $auditLog = $this->makeAuditLog();
        $resp = $this->auditLogRepo->delete($auditLog->id);
        $this->assertTrue($resp);
        $this->assertNull(AuditLog::find($auditLog->id), 'AuditLog should not exist in DB');
    }
}
