<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\CreateLineRequest;
use App\Http\Requests\Dashboard\UpdateLineRequest;
use App\Repositories\Dashboard\LineRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class LineController extends AppBaseController
{
    /** @var  LineRepository */
    private $lineRepository;

    public function __construct(LineRepository $lineRepo)
    {
        $this->lineRepository = $lineRepo;
    }

    /**
     * Display a listing of the Line.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->lineRepository->pushCriteria(new RequestCriteria($request));
        $lines = $this->lineRepository->all();

        return view('dashboard.lines.index')
            ->with('lines', $lines);
    }

    /**
     * Show the form for creating a new Line.
     *
     * @return Response
     */
    public function create()
    {
        return view('dashboard.lines.create');
    }

    /**
     * Store a newly created Line in storage.
     *
     * @param CreateLineRequest $request
     *
     * @return Response
     */
    public function store(CreateLineRequest $request)
    {
        $input = $request->all();

        $line = $this->lineRepository->create($input);

        Flash::success('Line saved successfully.');

        return redirect(route('dashboard.lines.index'));
    }

    /**
     * Display the specified Line.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $line = $this->lineRepository->findWithoutFail($id);

        if (empty($line)) {
            Flash::error('Line not found');

            return redirect(route('dashboard.lines.index'));
        }

        return view('dashboard.lines.show')->with('line', $line);
    }

    /**
     * Show the form for editing the specified Line.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $line = $this->lineRepository->findWithoutFail($id);

        if (empty($line)) {
            Flash::error('Line not found');

            return redirect(route('dashboard.lines.index'));
        }

        return view('dashboard.lines.edit')->with('line', $line);
    }

    /**
     * Update the specified Line in storage.
     *
     * @param  int              $id
     * @param UpdateLineRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLineRequest $request)
    {
        $line = $this->lineRepository->findWithoutFail($id);

        if (empty($line)) {
            Flash::error('Line not found');

            return redirect(route('dashboard.lines.index'));
        }

        $line = $this->lineRepository->update($request->all(), $id);

        Flash::success('Line updated successfully.');

        return redirect(route('dashboard.lines.index'));
    }

    /**
     * Remove the specified Line from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $line = $this->lineRepository->findWithoutFail($id);

        if (empty($line)) {
            Flash::error('Line not found');

            return redirect(route('dashboard.lines.index'));
        }

        $this->lineRepository->delete($id);

        Flash::success('Line deleted successfully.');

        return redirect(route('dashboard.lines.index'));
    }
}
