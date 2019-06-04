<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\CreateQueueRequest;
use App\Http\Requests\Dashboard\UpdateQueueRequest;
use App\Repositories\Dashboard\QueueRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class QueueController extends AppBaseController
{
    /** @var  QueueRepository */
    private $queueRepository;

    public function __construct(QueueRepository $queueRepo)
    {
        $this->queueRepository = $queueRepo;
    }

    /**
     * Display a listing of the Queue.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->queueRepository->pushCriteria(new RequestCriteria($request));
        $queues = $this->queueRepository->all();

        return view('dashboard.queues.index')
            ->with('queues', $queues);
    }

    /**
     * Show the form for creating a new Queue.
     *
     * @return Response
     */
    public function create()
    {
        return view('dashboard.queues.create');
    }

    /**
     * Store a newly created Queue in storage.
     *
     * @param CreateQueueRequest $request
     *
     * @return Response
     */
    public function store(CreateQueueRequest $request)
    {
        $input = $request->all();

        $queue = $this->queueRepository->create($input);

        Flash::success('Queue saved successfully.');

        return redirect(route('dashboard.queues.index'));
    }

    /**
     * Display the specified Queue.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            Flash::error('Queue not found');

            return redirect(route('dashboard.queues.index'));
        }

        return view('dashboard.queues.show')->with('queue', $queue);
    }

    /**
     * Show the form for editing the specified Queue.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            Flash::error('Queue not found');

            return redirect(route('dashboard.queues.index'));
        }

        return view('dashboard.queues.edit')->with('queue', $queue);
    }

    /**
     * Update the specified Queue in storage.
     *
     * @param  int              $id
     * @param UpdateQueueRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQueueRequest $request)
    {
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            Flash::error('Queue not found');

            return redirect(route('dashboard.queues.index'));
        }

        $queue = $this->queueRepository->update($request->all(), $id);

        Flash::success('Queue updated successfully.');

        return redirect(route('dashboard.queues.index'));
    }

    /**
     * Remove the specified Queue from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            Flash::error('Queue not found');

            return redirect(route('dashboard.queues.index'));
        }

        $this->queueRepository->delete($id);

        Flash::success('Queue deleted successfully.');

        return redirect(route('dashboard.queues.index'));
    }
}
