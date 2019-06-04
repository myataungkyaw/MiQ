<?php

use App\Models\Dashboard\Line;
use App\Repositories\Dashboard\LineRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LineRepositoryTest extends TestCase
{
    use MakeLineTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LineRepository
     */
    protected $lineRepo;

    public function setUp()
    {
        parent::setUp();
        $this->lineRepo = App::make(LineRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLine()
    {
        $line = $this->fakeLineData();
        $createdLine = $this->lineRepo->create($line);
        $createdLine = $createdLine->toArray();
        $this->assertArrayHasKey('id', $createdLine);
        $this->assertNotNull($createdLine['id'], 'Created Line must have id specified');
        $this->assertNotNull(Line::find($createdLine['id']), 'Line with given id must be in DB');
        $this->assertModelData($line, $createdLine);
    }

    /**
     * @test read
     */
    public function testReadLine()
    {
        $line = $this->makeLine();
        $dbLine = $this->lineRepo->find($line->id);
        $dbLine = $dbLine->toArray();
        $this->assertModelData($line->toArray(), $dbLine);
    }

    /**
     * @test update
     */
    public function testUpdateLine()
    {
        $line = $this->makeLine();
        $fakeLine = $this->fakeLineData();
        $updatedLine = $this->lineRepo->update($fakeLine, $line->id);
        $this->assertModelData($fakeLine, $updatedLine->toArray());
        $dbLine = $this->lineRepo->find($line->id);
        $this->assertModelData($fakeLine, $dbLine->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLine()
    {
        $line = $this->makeLine();
        $resp = $this->lineRepo->delete($line->id);
        $this->assertTrue($resp);
        $this->assertNull(Line::find($line->id), 'Line should not exist in DB');
    }
}
