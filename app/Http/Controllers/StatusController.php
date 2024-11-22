<?php

namespace App\Http\Controllers;

use App\Models\DeletedStatus;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StatusController extends Controller
{

    public function statusType(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate the incoming request data
            $request->validate([
                'status_type' => 'required|string|max:255',
                'id' => 'required|exists:statuses,id'  // Ensure the ID exists in the database
            ]);

            // Find the status type and update it
            $statusType = Status::findOrFail($request->id);
            $statusType->status_type = $request->status_type;
            $statusType->save();

            // Redirect back to the list with a success message
            return redirect()->route('status.statusType')->with('success', 'Status Type updated successfully!');
        }

        // Fetch all the status types from the database
        $statusTypes = Status::all();

        // Return the list of status types to the view
        return view('admin.pages.Status.formList', compact('statusTypes'));
    }

    public function statusArchive()
    {
        $statusTypes = Status::where('isArchived',1)->paginate(5);


        return view('admin.pages.Status.formList', compact('statusTypes'));
    }

    public function statusStore(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'status_type' => 'required|unique:statuses,status_type',
        ]);

        if ($validate->fails()) {
            notify()->error($validate->getMessageBag());
            return redirect()->back();
        }

        if (Str::lower($request->status_type) === 'reject' || Str::lower($request->status_type) === 'rejected') {
            notify()->error($request->status_type . ' is already in use for rejection');
            return redirect()->back();
        }

        if (Str::lower($request->status_type) === 'release' || Str::lower($request->status_type) === 'released') {
            notify()->error($request->status_type . ' is already in use for release status');
            return redirect()->back();
        }

        if (Str::lower($request->status_type) === 'complete' || Str::lower($request->status_type) === 'completed') {
            notify()->error($request->status_type . ' is already in use for completion status');
            return redirect()->back();
        }


        $status = Status::create([
            'statuses_id'=>$request->status_type,
            'status_type'=>$request->status_type
        ]);

        DeletedStatus::create([
            'statuses_id'=>$status->id,
            'status_type'=>$request->status_type
        ]);

        notify()->success('New Status created');
        return redirect()->back();
    }

    public function statusEdit($Id){
        $statusTypes = Status::find($Id);
        return view('admin.pages.Status.editList', compact('statusTypes'));
    }

    public function statusUpdate(Request $request, $id)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'status_type' => [
                'required',
                Rule::unique('statuses', 'status_type')->ignore($id),
            ],
        ]);

        // Handle validation failure
        if ($validate->fails()) {
            notify()->error($validate->getMessageBag()->first());
            return redirect()->back()->withErrors($validate)->withInput();
        }

        if (Str::lower($request->status_type) === 'reject' || Str::lower($request->status_type) === 'rejected') {
            notify()->error($request->status_type . ' is already in use for rejection');
            return redirect()->back();
        }

        if (Str::lower($request->status_type) === 'release' || Str::lower($request->status_type) === 'released') {
            notify()->error($request->status_type . ' is already in use for release');
            return redirect()->back();
        }

        // Update the document type
        $status = Status::find($id);
        if ($status) {
            $status->status_type = $request->status_type;
            $status->save();

            DeletedStatus::where('statuses_id',$id)->update([
                'status_type'=>$request->status_type
            ]);

            notify()->success('Staus updated successfully!');
        } else {
            notify()->error('Status not found!');
        }

        return redirect()->route('status.list');
    }

    public function statusDelete($Id)
    {
        Status::where('id', $Id)->delete();

        notify()->success('Status deleted successfully!');
        return redirect()->back();
    }

    public function statusSearch(Request $request){
        $searchTerm = $request->search;

        $statusTypes = Status::where(function ($query) use ($searchTerm) {
            $query->where('status_type', 'LIKE', '%' . $searchTerm . '%');
        })->paginate(5);

        $searchQuery = $searchTerm;

        return view('admin.pages.Status.formList', compact('statusTypes','searchQuery'));
    }

    //Archive Method
    public function restoreStatus($id)
    {
        $department = Status::find($id);
        if ($department) {
            $department->update(['isArchived'=>null]);
        }
        notify()->success('Status Restore Successfully.');
        return redirect()->back();
    }

    public function searchArchiveStatus(Request $request)
    {
        $searchTerm = $request->search;

        $statusTypes = Status::where(function ($query) use ($searchTerm) {
            $query->where('status_type', 'LIKE', '%' . $searchTerm . '%')
            ->where('isArchived',1);
        })->paginate(5);

        $searchQuery = $searchTerm;

        return view('admin.pages.Status.formList', compact('statusTypes','searchQuery'));
    }


}
