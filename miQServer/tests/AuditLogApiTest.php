<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditLogApiTest extends TestCase
{
    use MakeAuditLogTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateAuditLog()
    {
        $auditLog = $this->fakeAuditLogData();
        $this->json('POST', '/api/v1/auditLogs', $auditLog);

        $this->assertApiResponse($auditLog);
    }

    /**
     * @test
     */
    public function testReadAuditLog()
    {
        $auditLog = $this->makeAuditLog();
        $this->json('GET', '/api/v1/auditLogs/'.$auditLog->id);

        $this->assertApiResponse($auditLog->toArray());
    }

    /**
     * @test
     */
    public function testUpdateAuditLog()
    {
        $auditLog = $this->makeAuditLog();
        $editedAuditLog = $this->fakeAuditLogData();

        $this->json('PUT', '/api/v1/auditLogs/'.$auditLog->id, $editedAuditLog);

        $this->assertApiResponse($editedAuditLog);
    }

    /**
     * @test
     */
    public function testDeleteAuditLog()
    {
        $auditLog = $this->makeAuditLog();
        $this->json('DELETE', '/api/v1/auditLogs/'.$auditLog->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/auditLogs/'.$auditLog->id);

        $this->assertResponseStatus(404);
    }
}
