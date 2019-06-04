<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\CreatePrinterRequest;
use App\Http\Requests\Dashboard\UpdatePrinterRequest;
use App\Repositories\Dashboard\PrinterRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PrinterController extends AppBaseController
{
    /** @var  PrinterRepository */
    private $printerRepository;

    public function __construct(PrinterRepository $printerRepo)
    {
        $this->printerRepository = $printerRepo;
    }

    /**
     * Display a listing of the Printer.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->printerRepository->pushCriteria(new RequestCriteria($request));
        $printers = $this->printerRepository->paginate(12);

        return view('dashboard.printers.index')
            ->with('printers', $printers);
    }

    /**
     * Show the form for creating a new Printer.
     *
     * @return Response
     */
    public function create()
    {
        return view('dashboard.printers.create');
    }

    /**
     * Store a newly created Printer in storage.
     *
     * @param CreatePrinterRequest $request
     *
     * @return Response
     */
    public function store(CreatePrinterRequest $request)
    {
        $input = $request->all();

        $printer = $this->printerRepository->create($input);

        Flash::success('Printer saved successfully.');

        return redirect(route('dashboard.printers.index'));
    }

    /**
     * Display the specified Printer.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            Flash::error('Printer not found');

            return redirect(route('dashboard.printers.index'));
        }

        return view('dashboard.printers.show')->with('printer', $printer);
    }

    /**
     * Show the form for editing the specified Printer.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            Flash::error('Printer not found');

            return redirect(route('dashboard.printers.index'));
        }

        return view('dashboard.printers.edit')->with('printer', $printer);
    }

    /**
     * Update the specified Printer in storage.
     *
     * @param  int              $id
     * @param UpdatePrinterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePrinterRequest $request)
    {
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            Flash::error('Printer not found');

            return redirect(route('dashboard.printers.index'));
        }

        $printer = $this->printerRepository->update($request->all(), $id);

        Flash::success('Printer updated successfully.');

        return redirect(route('dashboard.printers.index'));
    }

    /**
     * Remove the specified Printer from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $printer = $this->printerRepository->findWithoutFail($id);

        if (empty($printer)) {
            Flash::error('Printer not found');

            return redirect(route('dashboard.printers.index'));
        }

        $this->printerRepository->delete($id);

        Flash::success('Printer deleted successfully.');

        return redirect(route('dashboard.printers.index'));
    }
}
