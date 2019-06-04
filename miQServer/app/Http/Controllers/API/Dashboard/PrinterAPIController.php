<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Requests\API\Dashboard\CreatePrinterAPIRequest;
use App\Http\Requests\API\Dashboard\UpdatePrinterAPIRequest;
use App\Models\Dashboard\Printer;
use App\Repositories\Dashboard\PrinterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PrinterController
 * @package App\Http\Controllers\API\Dashboard
 */

class PrinterAPIController extends AppBaseController
{
    /** @var  PrinterRepository */
    private $printerRepository;

    public function __construct(PrinterRepository $printerRepo)
    {
        $this->printerRepository = $printerRepo;
    }

    /**
     * Display a listing of the Printer.
     * GET|HEAD /printers
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->printerRepository->pushCriteria(new RequestCriteria($request));
        $this->printerRepository->pushCriteria(new LimitOffsetCriteria($request));
        $printers = $this->printerRepository->all();

        return $this->sendResponse($printers->toArray(), 'Printers retrieved successfully');
    }

    /**
     * Store a newly created Printer in storage.
     * POST /printers
     *
     * @param CreatePrinterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePrinterAPIRequest $request)
    {
        $input = $request->all();

        $printer = $this->printerRepository->create($input);

        return $this->sendResponse($printer->toArray(), 'Printer saved successfully');
    }

    /**
     * Display the specified Printer.
     * GET|HEAD /printers/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Printer $printer */
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            return $this->sendError('Printer not found');
        }

        return $this->sendResponse($printer->toArray(), 'Printer retrieved successfully');
    }

    /**
     * Update the specified Printer in storage.
     * PUT/PATCH /printers/{id}
     *
     * @param  int $id
     * @param UpdatePrinterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrinterAPIRequest $request)
    {
        $input = $request->all();

        /** @var Printer $printer */
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            return $this->sendError('Printer not found');
        }

        $printer = $this->printerRepository->update($input, $id);

        return $this->sendResponse($printer->toArray(), 'Printer updated successfully');
    }

    /**
     * Remove the specified Printer from storage.
     * DELETE /printers/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Printer $printer */
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            return $this->sendError('Printer not found');
        }

        $printer->delete();

        return $this->sendResponse($id, 'Printer deleted successfully');
    }
}
