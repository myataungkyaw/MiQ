<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\CreateAuditLogRequest;
use App\Http\Requests\Dashboard\UpdateAuditLogRequest;
use App\Repositories\Dashboard\AuditLogRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AuditLogController extends AppBaseController
{
    /** @var  AuditLogRepository */
    private $auditLogRepository;

    public function __construct(AuditLogRepository $auditLogRepo)
    {
        $this->auditLogRepository = $auditLogRepo;
    }

    /**
     * Display a listing of the AuditLog.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->auditLogRepository->pushCriteria(new RequestCriteria($request));
        $auditLogs = $this->auditLogRepository->paginate(10);

        return view('dashboard.audit_logs.index')
            ->with('auditLogs', $auditLogs);
    }

    /**
     * Show the form for creating a new AuditLog.
     *
     * @return Response
     */
    public function create()
    {
        return view('dashboard.audit_logs.create');
    }

    /**
     * Store a newly created AuditLog in storage.
     *
     * @param CreateAuditLogRequest $request
     *
     * @return Response
     */
    public function store(CreateAuditLogRequest $request)
    {
        $input = $request->all();

        $auditLog = $this->auditLogRepository->create($input);

        Flash::success('Audit Log saved successfully.');

        return redirect(route('dashboard.auditLogs.index'));
    }

    /**
     * Display the specified AuditLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            Flash::error('Audit Log not found');

            return redirect(route('dashboard.auditLogs.index'));
        }

        return view('dashboard.audit_logs.show')->with('auditLog', $auditLog);
    }

    /**
     * Show the form for editing the specified AuditLog.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            Flash::error('Audit Log not found');

            return redirect(route('dashboard.auditLogs.index'));
        }

        return view('dashboard.audit_logs.edit')->with('auditLog', $auditLog);
    }

    /**
     * Update the specified AuditLog in storage.
     *
     * @param  int              $id
     * @param UpdateAuditLogRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAuditLogRequest $request)
    {
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            Flash::error('Audit Log not found');

            return redirect(route('dashboard.auditLogs.index'));
        }

        $auditLog = $this->auditLogRepository->update($request->all(), $id);

        Flash::success('Audit Log updated successfully.');

        return redirect(route('dashboard.auditLogs.index'));
    }

    /**
     * Remove the specified AuditLog from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            Flash::error('Audit Log not found');

            return redirect(route('dashboard.auditLogs.index'));
        }

        $this->auditLogRepository->delete($id);

        Flash::success('Audit Log deleted successfully.');

        return redirect(route('dashboard.auditLogs.index'));
    }
}
