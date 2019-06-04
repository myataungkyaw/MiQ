<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Requests\API\Dashboard\CreateCompanyAPIRequest;
use App\Http\Requests\API\Dashboard\UpdateCompanyAPIRequest;
use App\Models\Dashboard\Company;
use App\Repositories\Dashboard\CompanyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Storage;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;


/**
 * Class CompanyController
 * @package App\Http\Controllers\API\Dashboard
 */

class CompanyAPIController extends AppBaseController
{
    /** @var  CompanyRepository */
    private $companyRepository;

    public function __construct(CompanyRepository $companyRepo)
    {
        $this->companyRepository = $companyRepo;
    }

    /**
     * Display a listing of the Company.
     * GET|HEAD /companies
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->companyRepository->pushCriteria(new RequestCriteria($request));
        $this->companyRepository->pushCriteria(new LimitOffsetCriteria($request));
        $companies = $this->companyRepository->all();

        foreach($companies as $company){
            if($company->logo){
                $company->logo = url('storage/uploads/companies/'.$company->logo);
            }
            if($company->background_image){
                $company->background_image = url('storage/uploads/backgrounds/'.$company->background_image);
            }

            if($company->notification_sound){
                $company->notification_sound = url('storage/uploads/sounds/'.$company->notification_sound);
            }

        }

        return $this->sendResponse($companies->toArray(), 'Companies retrieved successfully');
    }

    /**
     * Store a newly created Company in storage.
     * POST /companies
     *
     * @param CreateCompanyAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCompanyAPIRequest $request)
    {
       $input = $request->all();
       $logo = $request->file('logo');
       if(!empty($logo)){
        $filename = time().'.'.$logo->getClientOriginalExtension();
        Storage::disk('local')->putFileAs(
            'public/uploads/companies/',
            $logo,
            $filename
          );
         $input['logo'] = $filename;
       }


        $background_image = $request->file('background_image');
        if(!empty($background_image)){
            $filename = time().'.'.$background_image->getClientOriginalExtension();
            Storage::disk('local')->putFileAs(
                'public/uploads/companies/',
                $background_image,
                $filename
              );
             $input['background_image'] = $filename;
        }


        $company = $this->companyRepository->create($input);

        return $this->sendResponse($company, 'Company saved successfully');
    }


    

    /**
     * Display the specified Company.
     * GET|HEAD /companies/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Company $company */
        $company = $this->companyRepository->findWithoutFail($id);

        if (empty($company)) {
            return $this->sendError('Company not found');
        }

        if($company->logo){
            $company->logo = url('storage/uploads/companies/'.$company->logo);
        }
        if($company->background_image){
            $company->background_image = url('storage/uploads/backgrounds/'.$company->background_image);
        }

        if($company->notification_sound){
            $company->notification_sound = url('storage/uploads/sounds/'.$company->notification_sound);
        }



        return $this->sendResponse($company->toArray(), 'Company retrieved successfully');
    }

    /**
     * Update the specified Company in storage.
     * PUT/PATCH /companies/{id}
     *
     * @param  int $id
     * @param UpdateCompanyAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCompanyAPIRequest $request)
    {
        $input = $request->all();

        /** @var Company $company */
        $company = $this->companyRepository->findWithoutFail($id);

        if (empty($company)) {
            return $this->sendError('Company not found');
        }

        $logo = $request->file('logo');
        if(!empty($logo)){
         $filename = time().'.'.$logo->getClientOriginalExtension();
         Storage::disk('local')->putFileAs(
             'public/uploads/companies/',
             $logo,
             $filename
           );
          $input['logo'] = $filename;
        }
 
 
         $background_image = $request->file('background_image');
         if(!empty($background_image)){
             $filename = time().'.'.$background_image->getClientOriginalExtension();
             Storage::disk('local')->putFileAs(
                 'public/uploads/backgrounds/',
                 $background_image,
                 $filename
               );
              $input['background_image'] = $filename;
         }

         $notification_sound = $request->file('notification_sound');
         if(!empty($notification_sound)){
             $filename = time().'.'.$notification_sound->getClientOriginalExtension();
             Storage::disk('local')->putFileAs(
                 'public/uploads/sounds/',
                 $notification_sound,
                 $filename
               );
              $input['notification_sound'] = $filename;
         }


        $company = $this->companyRepository->update($input, $id);

        if($company->logo){
            $company->logo = url('storage/uploads/companies/'.$company->logo);
        }
        if($company->background_image){
            $company->background_image = url('storage/uploads/backgrounds/'.$company->background_image);
        }

        if($company->notification_sound){
            $company->notification_sound = url('storage/uploads/sounds/'.$company->notification_sound);
        }
        

        audit_log("Company", "Updated company information : ");

        return $this->sendResponse($company->toArray(), 'Company updated successfully');
    }

    /**
     * Remove the specified Company from storage.
     * DELETE /companies/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Company $company */
        $company = $this->companyRepository->findWithoutFail($id);

        if (empty($company)) {
            return $this->sendError('Company not found');
        }

        $company->delete();

        return $this->sendResponse($id, 'Company deleted successfully');
    }
}
