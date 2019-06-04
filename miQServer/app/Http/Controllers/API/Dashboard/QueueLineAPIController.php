<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Criteria\QueueLineCriteria;
use App\Repositories\Dashboard\QueueLineRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use App\Models\Dashboard\QueueLine;
use App\Models\Dashboard\Queue;

class QueueLineAPIController extends AppBaseController
{

    private $queueLineRepository;

    public function __construct(QueueLineRepository $queueRepo)
    {
        $this->queueLineRepository = $queueRepo;
    }

    public function index(Request $request)
    {
        $this->queueLineRepository->pushCriteria(new QueueLineCriteria($request));
        $this->queueLineRepository->pushCriteria(new LimitOffsetCriteria($request));
        $queueLines = $this->queueLineRepository->orderBy("position","ASC")->all();
        $statuses = config('miq.status');

        foreach($queueLines as $ql){
            $next_line = QueueLine::join("lines","lines.id","=","queue_lines.line_id")
            ->where("line_id", "!=", $ql->line_id)
            ->whereDate("queue_lines.created_at", today())
            ->where("queue_lines.queue_id", $ql->queue_id)
            ->where("queue_lines.status", "!=", QueueLine::DONE)
            ->select("lines.name")
            ->first();
            $ql->next_line = ($next_line) ? $next_line->name : "N.A";
            $ql->status_text = (isset($statuses[$ql->status])) ? $statuses[$ql->status] : "N.A";
        }

        return $this->sendResponse($queueLines->toArray(), 'Queue Lines retrieved successfully');
    }

    public function updateCallNumber(Request $request){
      //  return'hello';
        $id = $request->id;
        $call_number = $request->call_number;
        $queueLine = $this->queueLineRepository->with('line')->with('queue')->find($id);
        $queueLine->call_number = $call_number;
        $queueLine->line_desk_id = $request->desk_id;
        $queueLine->called_at = now();
        $queueLine->save();

        // $queue = Queue::find($queueLine->queue_id);
        event(new \App\Events\CallQueue($queueLine));

        return $this->sendResponse('','Update queue number successfully');
    }


    public function returnQ($id, Request $request){
        $ql =  QueueLine::find($id);
        if(!$ql){
             return $this->sendResponse([],'Queue Line not found!');
        }
        $ql->position = $ql->position+4;
        $ql->number_of_returnq += 1;
        $ql->save();
        return $this->sendResponse([],'Change Queue Position successfully');
    }

    public function finishQ($id, Request $request){
        $ql =  QueueLine::find($id);
        if(!$ql){
             return $this->sendResponse([],'Queue Line not found!');
        }
        $ql->status = QueueLine::DONE;
        $ql->finished_at = now();
        $ql->save();

        //release on hold 
        $oql = QueueLine::where("line_id", "!=", $ql->line_id)
        ->where("queue_id", $ql->queue_id)
        ->where("status", QueueLine::PENDING)
        ->whereDate("created_at", today())
        ->first();

         if($oql){
             $oql->on_hold = false;
             $oql->save();
         }


        return $this->sendResponse([],'Changed Queue Status successfully');
    }

    
    
}
