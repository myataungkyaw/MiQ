<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Criteria\QueueCriteriaCriteria;
use App\Http\Requests\API\Dashboard\CreateQueueAPIRequest;
use App\Http\Requests\API\Dashboard\UpdateQueueAPIRequest;
use App\Models\Dashboard\Queue;
use App\Models\Dashboard\QueueLine;
use App\Repositories\Dashboard\QueueRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Response;
use App\Models\Dashboard\Line;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use App\Models\Dashboard\Printer as PrinterModel;


/**
 * Class QueueController
 * @package App\Http\Controllers\API\Dashboard
 */

class QueueAPIController extends AppBaseController
{
    /** @var  QueueRepository */
    private $queueRepository;

    public function __construct(QueueRepository $queueRepo)
    {
        $this->queueRepository = $queueRepo;
    }

    /**
     * Display a listing of the Queue.
     * GET|HEAD /queues
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->queueRepository->pushCriteria(new QueueCriteriaCriteria($request));
        $this->queueRepository->pushCriteria(new LimitOffsetCriteria($request));

        $queues = $this->queueRepository->all();
        $statuses = config('miq.status');

        $queues->transform(function ($queue) {

            $queueCode = str_pad($queue->id,5,"0", STR_PAD_LEFT);
            $queue->queue_code = $queue->company->queue_prefix.$queueCode;
            
            // $qlines = $queue->queue_lines;
            // foreach($qlines as $ql){
            //     $ql->status_text = (isset($statuses[$ql->status])) ? $statuses[$ql->status] : "NA";
            // }
            // $queue->queue_lines = $qlines;

             return $queue;
        });

        return $this->sendResponse($queues->toArray(), 'Queues fetched successfully');
    }


    public function search(Request $request){

        $name = $request->get('name');
        $company_id = $request->get('company_id');
        $line_id = $request->get('line_id');
        $queue_id = $request->get('queue_id');

        $model = Queue::today();

            $model = $model->where(function ($query) use ($name) {
                $query->where("name", '=', $name)
                         ->orWhere('phone', '=', $name)
                         ->orWhere('queue_number', '=', $name);
            });
        
            

        if ($company_id){
            $model = $model->where('company_id', $company_id);
        }
           

        if ($queue_id){
            $model = $model->where('id', $queue_id);
        }
            
//        if ($line_id){
//            $model = $model->with( ['queueLines' => function($query) use ($line_id) {
//                $query->where('line_id', $line_id);
//            }]);

        if ($line_id){
            $model = $model->whereHas('queueLines', function ($query) use ($line_id) {
                $query->where('line_id', $line_id)->where('status', 0);
            });
        }

        $queues = $model->get();
        $statuses = config('miq.status');

        foreach($queues as $queue){

            $queue_lines = QueueLine::join("lines","lines.id","=","queue_lines.line_id")
            ->where("queue_id", $queue->id)
          //  ->where("queue_lines.status", 0)
            ->whereDate("queue_lines.created_at", today())
            ->select("queue_lines.*","lines.color","lines.name")
            ->orderBy("position","ASC")
            ->get();

            foreach($queue_lines as $ql){
                $ql->status_text = (isset($statuses[$ql->status])) ? $statuses[$ql->status] : "NA";
            }

            $queue->queue_lines = $queue_lines;

        }

        return $this->sendResponse($queues->toArray(), 'Queue searched successfully');
    }

    /**
     * Store a newly created Queue in storage.
     * POST /queues
     *
     * @param CreateQueueAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateQueueAPIRequest $request)
    {
        $input = $request->except('queue_lines');
        $queue_lines = $request->get('queue_lines');

        if(count($queue_lines)<1){
            return $this->sendError('You must provide a least one Line!');
        }


        $queues = $this->queueRepository->create($input);
        $queue_code = $queues->company->queue_prefix.str_pad($queues->id, 5,"0", STR_PAD_LEFT);
        $queues->queue_number = $queue_code;
        $queues->save();


        $i = 0;
        foreach($queue_lines as $k=>$v){

            if ($v["line_id"])
            {

                //position ? 
                //check existing queues in the line 
                $lastPositionQueue = QueueLine::whereDate("created_at", today())->where("line_id", $v["line_id"])->orderBy("position","DESC")->first();
                $pos = $lastPositionQueue ?  $lastPositionQueue->position + 1 : 1;
                $queue_line = [
                "queue_id"=>$queues->id, 
                "line_id"=>$v["line_id"], 
                "position"=> $pos,
                "on_hold"=> ($i>0) ? true : false
              ];
           $ql = QueueLine::create($queue_line);
           $i++;
            event(new \App\Events\IssueNewQueue($ql));
            }

        }

        
        $queues = $queues->with('company')->where('id', $queues->id)->first();
        $queues->queue_code = $queue_code;

        audit_log("Queue", "Issued a Queue : ID#".$queues->id);


         // $connector = new NetworkPrintConnector("10.x.x.x", 9100);
         //   $connector = new FilePrintConnector("php://stdout");
           // $connector = new FilePrintConnector("/dev/ttyS0");
            // $printer = new Printer($connector);
            // try {
            //     // ... Print stuff
            // } finally {
            //     $printer->close();
            // }



       //  $serverURL = "192.168.100.87";
        // $serverURL = "192.168.100.108";

         $printerDevice = PrinterModel::find($input["printer"]);
         $serverURL = $printerDevice->address;
        


         $companyName = $queues->company->name;
         $companyNote = $queues->company->note;

         $connector = new NetworkPrintConnector( $serverURL, 9100);
         //  $connector = new FilePrintConnector("php://stdout");
         //  $connector = new FilePrintConnector("/dev/ttyS0");
      

            $printer = new Printer($connector);

            $printer->initialize();
            try {
                // ... Print stuff
                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(1, 1);
                $printer->text("Welcome to ".$companyName."\n");
                $printer->text("Arrival:".$queues->created_at."\n");
                $printer->text(" \n");

                $printer->setTextSize(4, 4);
                $printer->text($queues->queue_number."\n");

                $printer->setTextSize(1, 1);
                $printer->text(" \n");
                if($companyNote!=null){
                $printer->text($companyNote."\n");
                }
               // $printer->text("လုပ်ငန်းစဉ်များအတွက် အရေးကြီးသည့်  \n");
                $printer->text(" \n");
                $printer->text(" \n");
                $printer->text(" \n");
                $printer->cut();

            } finally {
                $printer->close();
            }



        return $this->sendResponse($queues->toArray(), 'Queue saved successfully');
    }

    /**
     * Display the specified Queue.
     * GET|HEAD /queues/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Queue $queue */
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            return $this->sendError('Queue not found');
        }

        $queueCode = str_pad($queue->id,5,"0", STR_PAD_LEFT);
        $queue->queue_code = $queue->company->queue_prefix.$queueCode;
        $queue->queue_lines = QueueLine::join("lines","lines.id","=","queue_lines.line_id")
        ->where("queue_id", $queue->id)
        ->where("queue_lines.status", 0)
        ->select("queue_lines.*","lines.color","lines.name")
        ->orderBy("position","ASC")
        ->get();



        return $this->sendResponse($queue->toArray(), 'Queue retrieved successfully');
    }

    /**
     * Update the specified Queue in storage.
     * PUT/PATCH /queues/{id}
     *
     * @param  int $id
     * @param UpdateQueueAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQueueAPIRequest $request)
    {
        $input = $request->all();

        /** @var Queue $queue */
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            return $this->sendError('Queue not found');
        }
        $queue = $this->queueRepository->update($input, $id);
        return $this->sendResponse($queue->toArray(), 'Queue updated successfully');
    }

    /**
     * Remove the specified Queue from storage.
     * DELETE /queues/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Queue $queue */
        $queue = $this->queueRepository->findWithoutFail($id);

        if (empty($queue)) {
            return $this->sendError('Queue not found');
        }

        $queue->delete();

        return $this->sendResponse($id, 'Queue deleted successfully');
    }

    public function updateQueueLine(Request $request){
        $lineId = $request->line_id;
        $status = $request->status;
        $queue_line_id = $request->queue_id;
        $queueLine = QueueLine::find($queue_line_id)->update(['status'=>$status]);
        return $this->sendResponse('','Queue Line Update successfully');
    }

    public function addQueueLine(Request $request){
        $lineId = $request->line_id;
        $queueId = $request->queue_id;
      //  $existingQueueCount = QueueLine::whereDate("created_at", today())->where("line_id", $lineId)->count();
        $lastPositionQueue = QueueLine::whereDate("created_at", today())
        ->where("line_id", $lineId)
        ->orderBy("position","DESC")
        ->first();
        
        $position = ($lastPositionQueue) ? $lastPositionQueue->position+1 : 1;
        $queueLine = QueueLine::create(['queue_id'=>$queueId, 'line_id'=>$lineId, 'position'=>$position]);
        $line = Line::find($lineId);
        $queueLine->name = $line->name;
        $queueLine->color = $line->color;

        return $this->sendResponse($queueLine->toArray(),'Add Queue Line successfully');
    }

    public function  updateQueuePosition(Request $request){
        $queueLines = $request->queueLines;
        $i = 0;
        foreach($queueLines as $key=>$value){
            $queueLine = QueueLine::find($value['id']);
            $queueLine->position = $key+1;
            if($i>0){
            $queueLine->on_hold = true;
            }else{
            $queueLine->on_hold = false;
            }
            $queueLine->save();

            $i++;
        }
        return $this->sendResponse([],'Update Queue Position successfully');
    }

    public function changeStatus(Request $request){
        $queueLine = $request->queueLine;
        $status = $request->status;
        $queueLine = QueueLine::find($queueLine['id']);
        $queueLine->status = $status;
        $queueLine->save();
        return $this->sendResponse([],'Change Queue Status successfully');
    }

    public function serveQueueLine($id , Request $request){
        $desk_id = $request->input('line_desk_id');

       $ql =  QueueLine::find($id);
       if(!$ql){
            return $this->sendResponse([],'Queue not found!');
       }
       $ql->status = QueueLine::SERVING;
       $ql->started_at = now();
       $ql->line_desk_id = $desk_id;
       $ql->save();

       //set line_desk_id too 


       //set on hold for other line if exists
       $oqls = QueueLine::where("queue_id", $ql->queue_id)
       ->where("line_id", "!=", $ql->line_id)
       ->where("status", QueueLine::PENDING)
       ->get();
       foreach($oqls as $qlq){
           $qlq->on_hold = true;
           $qlq->save();
       }

       return $this->sendResponse([],'Change Queue Status successfully');
    }

    public function noshowQueueLine($id){
        $ql =  QueueLine::with('line')->with('queue')->find($id);
       if(!$ql){
            return $this->sendResponse([],'Queue not found!');
       }
       $ql->status = QueueLine::NO_SHOW;
       $ql->save();

       //release other lines 
       QueueLine::where("queue_id", $ql->queue_id)->where("line_id", "!=", $ql->line_id)->whereDate("created_at", today())->update(["on_hold"=>false]);

       
       event(new \App\Events\NoShowQueue($ql));

       return $this->sendResponse([],'Change Queue Status successfully');
    }

    public function displayScreens(Request $request){

         
    
        return $this->sendResponse([],'Display info fetched successfully');
    }

}
