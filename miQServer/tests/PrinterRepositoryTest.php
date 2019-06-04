<?php namespace Tests\Repositories;

use App\Models\Dashboard\Printer;
use App\Repositories\Dashboard\PrinterRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Traits\MakePrinterTrait;
use Tests\ApiTestTrait;

class PrinterRepositoryTest extends TestCase
{
    use MakePrinterTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PrinterRepository
     */
    protected $printerRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->printerRepo = \App::make(PrinterRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_printer()
    {
        $printer = $this->fakePrinterData();
        $createdPrinter = $this->printerRepo->create($printer);
        $createdPrinter = $createdPrinter->toArray();
        $this->assertArrayHasKey('id', $createdPrinter);
        $this->assertNotNull($createdPrinter['id'], 'Created Printer must have id specified');
        $this->assertNotNull(Printer::find($createdPrinter['id']), 'Printer with given id must be in DB');
        $this->assertModelData($printer, $createdPrinter);
    }

    /**
     * @test read
     */
    public function test_read_printer()
    {
        $printer = $this->makePrinter();
        $dbPrinter = $this->printerRepo->find($printer->id);
        $dbPrinter = $dbPrinter->toArray();
        $this->assertModelData($printer->toArray(), $dbPrinter);
    }

    /**
     * @test update
     */
    public function test_update_printer()
    {
        $printer = $this->makePrinter();
        $fakePrinter = $this->fakePrinterData();
        $updatedPrinter = $this->printerRepo->update($fakePrinter, $printer->id);
        $this->assertModelData($fakePrinter, $updatedPrinter->toArray());
        $dbPrinter = $this->printerRepo->find($printer->id);
        $this->assertModelData($fakePrinter, $dbPrinter->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_printer()
    {
        $printer = $this->makePrinter();
        $resp = $this->printerRepo->delete($printer->id);
        $this->assertTrue($resp);
        $this->assertNull(Printer::find($printer->id), 'Printer should not exist in DB');
    }
}
