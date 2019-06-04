<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakeTagTrait;
use Tests\ApiTestTrait;

class TagApiTest extends TestCase
{
    use MakeTagTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tag()
    {
        $tag = $this->fakeTagData();
        $this->response = $this->json('POST', '/api/dashboard/tags', $tag);

        $this->assertApiResponse($tag);
    }

    /**
     * @test
     */
    public function test_read_tag()
    {
        $tag = $this->makeTag();
        $this->response = $this->json('GET', '/api/dashboard/tags/'.$tag->id);

        $this->assertApiResponse($tag->toArray());
    }

    /**
     * @test
     */
    public function test_update_tag()
    {
        $tag = $this->makeTag();
        $editedTag = $this->fakeTagData();

        $this->response = $this->json('PUT', '/api/dashboard/tags/'.$tag->id, $editedTag);

        $this->assertApiResponse($editedTag);
    }

    /**
     * @test
     */
    public function test_delete_tag()
    {
        $tag = $this->makeTag();
        $this->response = $this->json('DELETE', '/api/dashboard/tags/'.$tag->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/dashboard/tags/'.$tag->id);

        $this->response->assertStatus(404);
    }
}
