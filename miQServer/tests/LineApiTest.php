<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LineApiTest extends TestCase
{
    use MakeLineTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateLine()
    {
        $line = $this->fakeLineData();
        $this->json('POST', '/api/v1/lines', $line);

        $this->assertApiResponse($line);
    }

    /**
     * @test
     */
    public function testReadLine()
    {
        $line = $this->makeLine();
        $this->json('GET', '/api/v1/lines/'.$line->id);

        $this->assertApiResponse($line->toArray());
    }

    /**
     * @test
     */
    public function testUpdateLine()
    {
        $line = $this->makeLine();
        $editedLine = $this->fakeLineData();

        $this->json('PUT', '/api/v1/lines/'.$line->id, $editedLine);

        $this->assertApiResponse($editedLine);
    }

    /**
     * @test
     */
    public function testDeleteLine()
    {
        $line = $this->makeLine();
        $this->json('DELETE', '/api/v1/lines/'.$line->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/lines/'.$line->id);

        $this->assertResponseStatus(404);
    }
}
