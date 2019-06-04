<?php

use App\Models\Dashboard\Queue;
use App\Repositories\Dashboard\QueueRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueueRepositoryTest extends TestCase
{
    use MakeQueueTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var QueueRepository
     */
    protected $queueRepo;

    public function setUp()
    {
        parent::setUp();
        $this->queueRepo = App::make(QueueRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateQueue()
    {
        $queue = $this->fakeQueueData();
        $createdQueue = $this->queueRepo->create($queue);
        $createdQueue = $createdQueue->toArray();
        $this->assertArrayHasKey('id', $createdQueue);
        $this->assertNotNull($createdQueue['id'], 'Created Queue must have id specified');
        $this->assertNotNull(Queue::find($createdQueue['id']), 'Queue with given id must be in DB');
        $this->assertModelData($queue, $createdQueue);
    }

    /**
     * @test read
     */
    public function testReadQueue()
    {
        $queue = $this->makeQueue();
        $dbQueue = $this->queueRepo->find($queue->id);
        $dbQueue = $dbQueue->toArray();
        $this->assertModelData($queue->toArray(), $dbQueue);
    }

    /**
     * @test update
     */
    public function testUpdateQueue()
    {
        $queue = $this->makeQueue();
        $fakeQueue = $this->fakeQueueData();
        $updatedQueue = $this->queueRepo->update($fakeQueue, $queue->id);
        $this->assertModelData($fakeQueue, $updatedQueue->toArray());
        $dbQueue = $this->queueRepo->find($queue->id);
        $this->assertModelData($fakeQueue, $dbQueue->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteQueue()
    {
        $queue = $this->makeQueue();
        $resp = $this->queueRepo->delete($queue->id);
        $this->assertTrue($resp);
        $this->assertNull(Queue::find($queue->id), 'Queue should not exist in DB');
    }
}
