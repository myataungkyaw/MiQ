<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakePrinterTrait;
use Tests\ApiTestTrait;

class PrinterApiTest extends TestCase
{
    use MakePrinterTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_printer()
    {
        $printer = $this->fakePrinterData();
        $this->response = $this->json('POST', '/api/dashboard/printers', $printer);

        $this->assertApiResponse($printer);
    }

    /**
     * @test
     */
    public function test_read_printer()
    {
        $printer = $this->makePrinter();
        $this->response = $this->json('GET', '/api/dashboard/printers/'.$printer->id);

        $this->assertApiResponse($printer->toArray());
    }

    /**
     * @test
     */
    public function test_update_printer()
    {
        $printer = $this->makePrinter();
        $editedPrinter = $this->fakePrinterData();

        $this->response = $this->json('PUT', '/api/dashboard/printers/'.$printer->id, $editedPrinter);

        $this->assertApiResponse($editedPrinter);
    }

    /**
     * @test
     */
    public function test_delete_printer()
    {
        $printer = $this->makePrinter();
        $this->response = $this->json('DELETE', '/api/dashboard/printers/'.$printer->id);

        $this->assertApiSuccess();
        $this->response = $this->json('GET', '/api/dashboard/printers/'.$printer->id);

        $this->response->assertStatus(404);
    }
}
