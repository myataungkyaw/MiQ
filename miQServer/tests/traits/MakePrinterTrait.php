<?php namespace Tests\Traits;

use Faker\Factory as Faker;
use App\Models\Dashboard\Printer;
use App\Repositories\Dashboard\PrinterRepository;

trait MakePrinterTrait
{
    /**
     * Create fake instance of Printer and save it in database
     *
     * @param array $printerFields
     * @return Printer
     */
    public function makePrinter($printerFields = [])
    {
        /** @var PrinterRepository $printerRepo */
        $printerRepo = \App::make(PrinterRepository::class);
        $theme = $this->fakePrinterData($printerFields);
        return $printerRepo->create($theme);
    }

    /**
     * Get fake instance of Printer
     *
     * @param array $printerFields
     * @return Printer
     */
    public function fakePrinter($printerFields = [])
    {
        return new Printer($this->fakePrinterData($printerFields));
    }

    /**
     * Get fake data of Printer
     *
     * @param array $printerFields
     * @return array
     */
    public function fakePrinterData($printerFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'address' => $fake->word,
            'status' => $fake->randomDigitNotNull,
            'created_at' => $fake->date('Y-m-d H:i:s'),
            'updated_at' => $fake->date('Y-m-d H:i:s')
        ], $printerFields);
    }
}
