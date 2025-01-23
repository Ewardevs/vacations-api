<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveRequest;
use App\Http\Requests\StoreVacationRequest;
use App\Http\Requests\UpdateVacationRequest;
use App\Http\Resources\VacationResource;
use App\Models\User;
use App\Models\Vacation;
use Illuminate\Support\Facades\Auth;

class VacationController extends BaseController
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->hasRole("Admin")) {
            $vacations = Vacation::with(['applicant', 'approver'])->get();
            return $this->sendResponse(VacationResource::collection($vacations), 'Vacations retrieved successfully.');
        } else {
            $vacations = Vacation::where("applicant_id",Auth()->user()->id)->with(['applicant', 'approver'])->get();
            return $this->sendResponse(VacationResource::collection($vacations), 'Vacations retrieved successfully.');
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVacationRequest $request)
    {
        $hasVacation = Vacation::where("applicant_id",Auth()->user()->id)->count();

        if ($hasVacation >=1){
            return $this->sendError("You can not have more vacation");
        }

        $vacation = Vacation::create([
            "applicant_id"=>Auth::user()->id,
            "message_applicant"=> $request->message_applicant,
            "start_date"=> $request->start_date,
            "end_date"=>  $request->end_date
        ]);

        $vacation->save();

        $vacation->load(['applicant', 'approver']);

        return $this->sendResponse(new VacationResource($vacation), 'Vacation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vacation = Vacation::with(['applicant', 'approver'])->find($id);

        $user = User::find(Auth()->user()->id);



        if (!$vacation){
            return $this->sendError('Vacation not found.',);
        }

        return $this->sendResponse(new VacationResource($vacation), 'Vacation retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVacationRequest $request, $id)
    {
        $vacation = Vacation::with(['applicant', 'approver'])->find($id);
        if (!$vacation){
            return $this->sendError('Vacation not found.',);
        }
        $vacation->update($request->all());
        $vacation->load(['applicant','approver']);

        return $this->sendResponse(new VacationResource($vacation), 'Vacation updated successfully.');
    }

    public function approve(ApproveRequest $request, $id)
    {
        $vacation = Vacation::with(['applicant', 'approver'])->find($id);
        if (!$vacation){
            return $this->sendError('Vacation not found.',);
        }

        if ($vacation->status === 'approved' || $vacation->status === 'rejected') {
            return $this->sendError("Vacation couldn't be aproved because it's approved" );
        }

        $vacation->approver_id = $request->user()->id;
        $vacation->message_approver = $request->message_approver;
        $vacation->status = 'approved';
        $vacation->save();
        $vacation->load(['applicant','approver']);

        return $this->sendResponse(new VacationResource($vacation), 'Vacation approved successfully.');
    }

    public function reject(ApproveRequest $request, $id)
    {
        $vacation = Vacation::with(['applicant', 'approver'])->find($id);
        if (!$vacation){
            return $this->sendError('Vacation not found.',);
        }

        if ($vacation->status == 'approved' || $vacation->status == 'rejected') {
            return $this->sendError( "Vacation couldn't be aproved because it's rejected");
        }

        $vacation->approver_id = $request->user()->id;
        $vacation->message_approver = $request->message_approver;
        $vacation->status = 'rejected';
        $vacation->save();
        $vacation->load(['applicant','approver']);

        return $this->sendResponse(new VacationResource($vacation), 'Vacation rejected successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $vacation = Vacation::with(['applicant', 'approver'])->find($id);
        if (!$vacation){
            return $this->sendError('Vacation not found.',);
        }

        $vacation->delete();
        $vacation->load(['applicant','approver']);
        return  $this->sendResponse(new VacationResource($vacation), 'Vacation deleted successfully.');
    }
}
