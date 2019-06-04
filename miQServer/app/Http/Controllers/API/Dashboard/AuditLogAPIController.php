<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Criteria\AuditCriteria;
use App\Http\Requests\API\Dashboard\CreateAuditLogAPIRequest;
use App\Http\Requests\API\Dashboard\UpdateAuditLogAPIRequest;
use App\Models\Dashboard\AuditLog;
use App\Repositories\Dashboard\AuditLogRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Response;

/**
 * Class AuditLogController
 * @package App\Http\Controllers\API\Dashboard
 */

class AuditLogAPIController extends AppBaseController
{
    /** @var  AuditLogRepository */
    private $auditLogRepository;

    public function __construct(AuditLogRepository $auditLogRepo)
    {
        $this->auditLogRepository = $auditLogRepo;
    }

    /**
     * Display a listing of the AuditLog.
     * GET|HEAD /auditLogs
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->auditLogRepository->pushCriteria(new AuditCriteria($request));
        $this->auditLogRepository->pushCriteria(new LimitOffsetCriteria($request));
        $auditLogs = $this->auditLogRepository->with('user')->scopeQuery(function($query){
            return $query->orderBy('audit_logs.created_at','desc');
        })->paginate(10);

        return $this->sendResponse($auditLogs->toArray(), 'Audit Logs retrieved successfully');
    }

    /**
     * Store a newly created AuditLog in storage.
     * POST /auditLogs
     *
     * @param CreateAuditLogAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAuditLogAPIRequest $request)
    {
        $input = $request->all();

        $auditLogs = $this->auditLogRepository->create($input);

        return $this->sendResponse($auditLogs->toArray(), 'Audit Log saved successfully');
    }

    /**
     * Display the specified AuditLog.
     * GET|HEAD /auditLogs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AuditLog $auditLog */
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            return $this->sendError('Audit Log not found');
        }

        return $this->sendResponse($auditLog->toArray(), 'Audit Log retrieved successfully');
    }

    /**
     * Update the specified AuditLog in storage.
     * PUT/PATCH /auditLogs/{id}
     *
     * @param  int $id
     * @param UpdateAuditLogAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAuditLogAPIRequest $request)
    {
        $input = $request->all();

        /** @var AuditLog $auditLog */
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            return $this->sendError('Audit Log not found');
        }

        $auditLog = $this->auditLogRepository->update($input, $id);

        return $this->sendResponse($auditLog->toArray(), 'AuditLog updated successfully');
    }

    /**
     * Remove the specified AuditLog from storage.
     * DELETE /auditLogs/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AuditLog $auditLog */
        $auditLog = $this->auditLogRepository->findWithoutFail($id);

        if (empty($auditLog)) {
            return $this->sendError('Audit Log not found');
        }

        $auditLog->delete();

        return $this->sendResponse($id, 'Audit Log deleted successfully');
    }
}
