<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Criteria\LineCriteria;
use App\Http\Requests\API\Dashboard\CreateLineAPIRequest;
use App\Http\Requests\API\Dashboard\UpdateLineAPIRequest;
use App\Models\Dashboard\LineDesk;
use App\Models\Dashboard\Line;
use App\Repositories\Dashboard\LineRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Response;
use App\Models\Dashboard\QueueLine;
use App\Models\Dashboard\Tag;

/**
 * Class LineController
 * @package App\Http\Controllers\API\Dashboard
 */

class LineAPIController extends AppBaseController
{
    /** @var  LineRepository */
    private $lineRepository;

    public function __construct(LineRepository $lineRepo)
    {
        $this->lineRepository = $lineRepo;
    }

    /**
     * Display a listing of the Line.
     * GET|HEAD /lines
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->lineRepository->pushCriteria(new LineCriteria($request));
        $this->lineRepository->pushCriteria(new LimitOffsetCriteria($request));
        $lines = $this->lineRepository->with('lineDesks')->orderBy("priority","ASC")->all();
        // foreach($lines as $l){
        //     $l->total_waiting = QueueLine::today()->pending()->count();
        // }

        // // $tags = Tag::all();
        //  $tagged = [];
        // // foreach($tags as $tag){
         
        // // }

        // foreach($lines as $line){
        //     $my_tags = explode(",",$line->tags);
        //     foreach($my_tags as $mt){
        //         $tagged[$mt][] = $line;
        //     }
        // }

       //   return $this->sendResponse($tagged ,'Lines retrieved successfully');
       return $this->sendResponse($lines->toArray(), 'Lines retrieved successfully');
    }

    /**
     * Store a newly created Line in storage.
     * POST /lines
     *
     * @param CreateLineAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateLineAPIRequest $request)
    {
        $input = $request->except('desks');
        $desks = $request->get('desks');
        $tags = $request->get('tags');

        if(is_array($tags)){
         $t = "";
         $i = 0;
         foreach($tags as $tag){
             $t .= ($i >0)  ? ",".$tag["text"] : $tag["text"];
             Tag::firstOrCreate(["name"=>$tag["text"]]);
             $i++;
         }
         $input["tags"] = $t;
        }

        $lines = $this->lineRepository->create($input);

        foreach($desks as $k=>$v){
            $desk = ["line_id"=>$lines->id, "name"=>$v["name"]];
            LineDesk::create($desk);
        }

        return $this->sendResponse($lines->toArray(), 'Line saved successfully');
    }

    /**
     * Display the specified Line.
     * GET|HEAD /lines/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Line $line */
        $line = $this->lineRepository->with('lineDesks')->findWithoutFail($id);
       // dd($line->lineDesks);

        if (empty($line)) {
            return $this->sendError('Line not found');
        }

        $line->tags = (!empty($line->tags)) ? explode(",", $line->tags) : [];

        return $this->sendResponse($line->toArray(), 'Line retrieved successfully');
    }

    /**
     * Update the specified Line in storage.
     * PUT/PATCH /lines/{id}
     *
     * @param  int $id
     * @param UpdateLineAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLineAPIRequest $request)
    {
        $input = $request->except('desks');

        /** @var Line $line */
        $line = $this->lineRepository->findWithoutFail($id);

        if (empty($line)) {
            return $this->sendError('Line not found');
        }


        $tags = $request->get('tags');

        if(is_array($tags)){
         $t = "";
         $i = 0;
         foreach($tags as $tag){
             $t .= ($i >0)  ? ",".$tag["text"] : $tag["text"];
             Tag::firstOrCreate(["name"=>$tag["text"]]);
             $i++;
         }
         $input["tags"] = $t;
        }

        

        $line = $this->lineRepository->update($input, $id);

        //delete desks
        $desks = $request->get('desks');
        $desk_ids = array_pluck($desks, 'id');
        LineDesk::where("line_id", $line->id)->whereNotIn('id', $desk_ids)->delete();

        //add or update desks
        foreach($desks as $k=>$v){
            $desk = ["line_id"=>$line->id, "name"=>$v["name"]];
            LineDesk::updateOrCreate(["id"=>$v['id']], $desk);
        }




        return $this->sendResponse($line->toArray(), 'Line updated successfully');
    }

    /**
     * Remove the specified Line from storage.
     * DELETE /lines/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Line $line */
        $line = $this->lineRepository->findWithoutFail($id);

        if (empty($line)) {
            return $this->sendError('Line not found');
        }

        $line->delete();

        return $this->sendResponse($id, 'Line deleted successfully');
    }

    public  function getLinesByCompany($company_id)
    {
        $lines = $this->lineRepository->getLineByCompany($company_id);


          // $tags = Tag::all();
          $tagged = [];
          // foreach($tags as $tag){
           
          // }
  
          foreach($lines as $line){
              $my_tags = explode(",",$line->tags);
              foreach($my_tags as $mt){
                  $tagged[$mt][] = $line;
              }
          }


      //  return $this->sendResponse($lines->toArray(), 'Company Lines retrieved successfully');
        return $this->sendResponse(["lines"=>$lines->toArray(),"tagged"=>$tagged], 'Company Lines retrieved successfully');
    }

    public function getDesksByCompany($company_id){
        $linedesks = LineDesk::join("lines","lines.id","=","line_desks.line_id")
                                         ->where("lines.company_id", $company_id)
                                         ->orderBy("lines.priority", "ASC")
                                         ->select("line_desks.*","lines.name as line_name")
                                         ->get();

        return $this->sendResponse($linedesks->toArray(), 'Company Desks retrieved successfully');
    }

}
