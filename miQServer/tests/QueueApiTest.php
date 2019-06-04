<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueueApiTest extends TestCase
{
    use MakeQueueTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateQueue()
    {
        $queue = $this->fakeQueueData();
        $this->json('POST', '/api/v1/queues', $queue);

        $this->assertApiResponse($queue);
    }

    /**
     * @test
     */
    public function testReadQueue()
    {
        $queue = $this->makeQueue();
        $this->json('GET', '/api/v1/queues/'.$queue->id);

        $this->assertApiResponse($queue->toArray());
    }

    /**
     * @test
     */
    public function testUpdateQueue()
    {
        $queue = $this->makeQueue();
        $editedQueue = $this->fakeQueueData();

        $this->json('PUT', '/api/v1/queues/'.$queue->id, $editedQueue);

        $this->assertApiResponse($editedQueue);
    }

    /**
     * @test
     */
    public function testDeleteQueue()
    {
        $queue = $this->makeQueue();
        $this->json('DELETE', '/api/v1/queues/'.$queue->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/queues/'.$queue->id);

        $this->assertResponseStatus(404);
    }
}
