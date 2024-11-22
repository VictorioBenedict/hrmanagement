<?php

namespace App\Http\Controllers;

use App\Mail\DocumentMail;
use App\Mail\LeaveMail;
use App\Mail\RejectLeaveMail;
use App\Models\DeletedStatus;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\LeaveFields;
use App\Models\LeaveTypes;
use App\Models\Status;
use App\Models\Task;
use App\Models\Documents;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Node\Block\Document;

class LeaveController extends Controller
{

    public function leave()
    {
        $leaves = Leave::orderBy('id','desc')->all();
        $leaveTypes = LeaveType::orderBy('id','desc')->all();


        return view('admin.pages.Leave.leaveForm', compact('leaves', 'leaveTypes'));
    }
    public function leaveList()
    {

        $leaves = Leave::whereNotIn('statuses_id', [0, 999])->orderBy('id','desc')->paginate(5);

        $searchQuery = null;

        $deletedStatuses = [];

        foreach ($leaves as $document) {
            if ($document->statuses_id) {
                $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                if ($deletedStatus) {
                    $deletedStatuses[$document->id] = $deletedStatus->status_type;
                } else {
                    $deletedStatuses[$document->id] = null; // Handle case where there's no matching DeletedStatus
                }
            }
        }
        $rejectedLeaves = Leave::with(['employee.department'])
        ->where('statuses_id', 0)->orderBy('id','desc')->get();

        $receivedLeaves= Leave::with(['employee.department'])
        ->where('statuses_id',999)->orderBy('id','desc')->get();
        $statusTypes = Status::all();

        $filteredUsers = User::where('role', 'System Admin')
        ->with(['employee.designation'])
        ->get();

        // Example of filtering users with a specific designation name
        $systemAdminUsers = $filteredUsers->filter(function ($user) {
            return $user->employee = 'user';
        });

        return view('admin.pages.Leave.leaveList', compact('leaves','rejectedLeaves', 'receivedLeaves','searchQuery','deletedStatuses','statusTypes','systemAdminUsers'));
    }


    public function myLeave()
    {
        $userId = auth()->user()->id;
        // Retrieve leave records for the authenticated user only
        $leaves = Leave::where('employee_id', $userId)
            ->with(['type'])
            ->paginate(5);

        return view('admin.pages.Leave.myLeave', compact('leaves'));
    }

    // public function store(Request $request)
    // {
    //     $validate = Validator::make($request->all(), [
    //         'from_date' => 'required|date',
    //         'to_date' => 'required|date|after_or_equal:from_date',
    //         'leave_type_id' => 'required',
    //         'description' => 'required',
    //     ]);

    //     if ($validate->fails()) {
    //         notify()->error($validate->getMessageBag());
    //         return redirect()->back();
    //     }

    //     $fromDate = Carbon::parse($request->from_date);
    //     $toDate = Carbon::parse($request->to_date);
    //     $totalDays = $toDate->diffInDays($fromDate) + 1; // Calculate total days

    //     // Fetch the total days for the selected leave type ('leave_days' column)
    //     $leaveType = LeaveType::findOrFail($request->leave_type_id);
    //     $leaveTypeTotalDays = $leaveType->leave_days; // Assuming 'leave_days' is the field in the LeaveType model

    //     // Validate if the total days taken for this leave type don't exceed the available days
    //     $userId = auth()->user()->id;
    //     $totalTakenDaysForLeaveType = Leave::where('employee_id', $userId)
    //         ->where('leave_type_id', $request->leave_type_id)
    //         ->whereYear('from_date', '=', date('Y'))
    //         ->sum('total_days');

    //     $availableLeaveDays = $leaveTypeTotalDays - $totalTakenDaysForLeaveType;

    //     if ($totalDays > $availableLeaveDays) {
    //         notify()->error('Exceeded available leave days for this type.');
    //         return redirect()->back();
    //     }

    //     Leave::create([
    //         'employee_name' => auth()->user()->name,
    //         'employee_id' => auth()->user()->id,
    //         'department_name' => auth()->user()->employee->department->department_name,
    //         'designation_name' => auth()->user()->employee->designation->designation_name,
    //         'from_date' => $fromDate,
    //         'to_date' => $toDate,
    //         'total_days' => $totalDays,
    //         'leave_type_id' => $request->leave_type_id,
    //         'description' => $request->description,
    //     ]);

    //     notify()->success('New Leave created');
    //     return redirect()->back();
    // }



    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'leave_type_id' => 'required',
        ]);

        if ($validate->fails()) {
            notify()->error($validate->getMessageBag());
            return redirect()->back();
        }

        // Ensure 'from_date' is not in the past
        $today = Carbon::today();
        $fromDate = Carbon::parse($request->from_date);

        if ($fromDate->lessThanOrEqualTo($today)) {
            notify()->error('Leave start date should be a future date.');
            return redirect()->back();
        }


        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date);
        $totalDays = $toDate->diffInDays($fromDate) + 1; // Calculate total days

        $leaveType = LeaveType::findOrFail($request->leave_type_id);
        $leaveTypeTotalDays = $leaveType->leave_days;

        $userId = auth()->user()->id;

        $totalTakenDaysForLeaveType = Leave::where('employee_id', $userId)
            ->where('leave_type_id', $request->leave_type_id)
            ->where('status', 'approved')
            ->sum('total_days');

        if (($totalTakenDaysForLeaveType + $totalDays) > $leaveTypeTotalDays) {
            notify()->error('Exceeds available leave days for this type.');
            return redirect()->back();
        }


        // Check if this is the first leave for the employee
        $firstLeave = Leave::where('employee_id', $userId)->count() === 0;

        if (!$firstLeave) {
            // Check if the employee's first leave is rejected or approved by the admin
            $firstLeaveStatus = Leave::where('employee_id', $userId)
                ->where('status', '!=', 'pending') // Exclude pending status (includes rejected and approved)
                ->orderBy('created_at', 'asc')
                ->value('status');

            if ($firstLeaveStatus === 'rejected') {
                // Allow reapplication if the first leave was rejected
                $firstLeaveStatus = 'approved';
            }

            if ($firstLeaveStatus !== 'approved') {
                notify()->error('You cannot take leave until your first leave is approved by the admin.');
                return redirect()->back();
            }
        }

        // Check if the previous leave's end date has passed
        $previousLeaveEndDate = Leave::where('employee_id', $userId)
            ->where('status', 'approved')
            ->orderBy('to_date', 'desc')
            ->value('to_date');

        if ($previousLeaveEndDate && Carbon::parse($previousLeaveEndDate)->isFuture()) {
            notify()->error('You cannot take leave until your previous leave date is over.');
            return redirect()->back();
        }

        Leave::create([
            'employee_name' => auth()->user()->name,
            'department_name' => optional(auth()->user()->employee->department)->department_name ?? 'Not specified',
            'designation_name' => optional(auth()->user()->employee->designation)->designation_name ?? 'Not specified',
            'employee_id' => $userId,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'total_days' => $totalDays,
            'leave_type_id' => $request->leave_type_id,
            'description' => $request->description,
        ]);

        notify()->success('New Leave created');
        return redirect()->back();
    }



    // Approve and Reject Leave
    public function approveLeave($id)
    {
        $leave = Leave::find($id);
        $leave->status = 'approved'; // Assuming 'status' is a field in your 'leaves' table
        $leave->save();

        notify()->success('Leave approved');
        return redirect()->back();
    }

    public function rejectLeave($id)
    {
        $leave = Leave::find($id);
        $leave->status = 'rejected'; // Assuming 'status' is a field in your 'leaves' table
        $leave->save();

        notify()->error('Leave rejected');
        return redirect()->back();
    }

    // Leave Type
    public function leaveType()
    {
        $leaveTypes = LeaveType::all();
        return view('admin.pages.leaveType.formList', compact('leaveTypes'));
    }

    public function leaveStore(Request $request)
    {
        // dd($request->all());

        $validate = Validator::make($request->all(), [
            'leave_type_id' => 'required|string|unique:leave_types,leave_type_id',
            'leave_days' => 'min:0',
        ]);

        if ($validate->fails()) {
            notify()->error($validate->getMessageBag());
            return redirect()->back();
        }

        $leaveType = LeaveType::create([
            'leave_type_id' => $request->leave_type_id,
            'leave_days' => $request->leave_days,
        ]);

        LeaveFields::create([
            'leave_fieldname'=>$request->leave_type_id,
            'leave_type_id'=>$leaveType->id,
            'is_visible'=>1
        ]);

        notify()->success('New Leave Type created successfully.');
        return redirect()->back();
    }

    // edit, delete, update LeaveType


    public function LeaveDelete($id)
    {
        $leaveType = LeaveType::find($id);
        if ($leaveType) {
            $leaveType->delete();
        }
        notify()->success('Deleted Successfully.');
        return redirect()->back();
    }
    public function leaveEdit($id)
    {
        $leaveType = LeaveType::find($id);
        return view('admin.pages.leaveType.editList', compact('leaveType'));
    }
    public function LeaveUpdate(Request $request, $id)
    {

         // Validate the request
         $validate = Validator::make($request->all(), [
            'leave_type_id' => [
                'required',
                Rule::unique('leave_types', 'leave_type_id')->ignore($id),
            ],
        ]);

        // Handle validation failure
        if ($validate->fails()) {
            notify()->error($validate->getMessageBag()->first());
            return redirect()->back()->withErrors($validate)->withInput();
        }


        $leaveType = LeaveType::find($id);
        if ($leaveType) {

            $leaveType->update([
                'leave_type_id' => $request->leave_type_id,
                'leave_days' => $request->leave_days,
            ]);

            notify()->success('Your information updated successfully.');
            return redirect()->route('leave.leaveType');
        }
    }


    // leave Balance

    // public function showLeaveBalance()
    // {
    //     $userId = auth()->user()->id;

    //     // Retrieve leaves with type for the current user in the current year
    //     $leaves = Leave::where('employee_id', $userId)
    //         ->whereYear('from_date', '=', date('Y'))
    //         ->where('status', 'approved') // Only consider approved leaves
    //         ->with('type')
    //         ->get();

    //     // Initialize variables
    //     $leaveTypeBalances = [];
    //     $totalTakenDays = 0;

    //     // Calculate leave balances
    //     foreach ($leaves as $leave) {
    //         $leaveType = $leave->type->leave_type_id;

    //         if (!isset($leaveTypeBalances[$leaveType])) {
    //             $leaveLimit = $leave->type->leave_days;

    //             $leaveTypeBalances[$leaveType] = [
    //                 'leaveType' => $leave->type->name,
    //                 'totalDays' => $leaveLimit,
    //                 'takenDays' => 0,
    //                 'availableDays' => $leaveLimit,
    //             ];
    //         }

    //         // Update taken and available days
    //         $leaveTypeBalances[$leaveType]['takenDays'] += $leave->total_days;
    //         $leaveTypeBalances[$leaveType]['availableDays'] -= $leave->total_days;

    //         $totalTakenDays += $leave->total_days; // Track total taken days
    //     }

    //     return view('admin.pages.Leave.myLeaveBalance', compact('leaveTypeBalances', 'totalTakenDays'));
    // }






    public function showLeaveBalance()
    {
        $userId = auth()->user()->id;
        $designation = auth()->user()->employee->designation->designation_name;

        // Define leave days based on designations
        $designationLeaveDays = [
            'Admin IV' => 20,
            'teacher 1' => 20,
            'Manager' => 25,
        ];

        $leaveTypeBalances = [];
        $totalTakenDays = 0;

        $leaves = Leave::where('employee_id', $userId)
            ->whereYear('from_date', '=', date('Y'))
            ->with('type')
            ->get();

        foreach ($leaves as $leave) {
            $leaveType = $leave->type->leave_type_id;
            $leaveLimit = $leave->type->leave_days;

            if (!isset($leaveTypeBalances[$leaveType])) {
                $leaveTypeBalances[$leaveType] = [
                    'totalDays' => $leaveLimit,
                    'takenDays' => 0,
                    'availableDays' => $leaveLimit,
                ];
            }

            if ($leave->status === 'approved') {
                $leaveTypeBalances[$leaveType]['takenDays'] += $leave->total_days;
                $leaveTypeBalances[$leaveType]['availableDays'] -= $leave->total_days;

                $totalTakenDays += $leave->total_days;
            }
        }

        // Update available days based on designation
        $availableDays = $designationLeaveDays[$designation] - $totalTakenDays;
        $leaveTypeBalances[$designation] = [
            'totalDays' => $designationLeaveDays[$designation],
            'takenDays' => $totalTakenDays,
            'availableDays' => $availableDays,
        ];

        return view('admin.pages.Leave.myLeaveBalance', compact('leaveTypeBalances', 'totalTakenDays', 'designationLeaveDays', 'designation', 'availableDays'));
    }







    // single employee report
    public function allLeaveReport()
    {
        $leaves = Leave::where('status', 'approved')
            ->with(['type'])
            ->paginate(5);

        return view('admin.pages.Leave.allLeaveReport', compact('leaves'));
    }


    // single employee leave
    public function myLeaveReport()
    {
        $userId = auth()->user()->id;

        // Retrieve only approved leave records for the authenticated user
        $leaves = Leave::where('employee_id', $userId)
            ->where('status', 'approved') // Fetch only approved leaves
            ->with(['type'])
            ->paginate(5);

        return view('admin.pages.Leave.myLeaveReport', compact('leaves'));
    }

    // search leaveList
    public function searchLeaveList(Request $request)
    {
        $searchTerm = $request->search;

        $query = Leave::with(['type']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('employee_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('type', function ($typeQuery) use ($searchTerm) {
                        $typeQuery->where('leave_type_id', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('from_date', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('to_date', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('total_days', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('department_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('designation_name', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $leaves = $query->paginate(5);

        return view('admin.pages.Leave.searchLeaveList', compact('leaves'));
    }





    // search my leave
    public function searchMyLeave(Request $request)
    {
        $userId = auth()->user()->id;
        $searchTerm = $request->search;

        $query = Leave::where('employee_id', $userId)->with('type');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('type', function ($typeQuery) use ($searchTerm) {
                    $typeQuery->where('leave_type_id', 'LIKE', '%' . $searchTerm . '%');
                })
                    ->orWhere('from_date', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('to_date', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('total_days', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('status', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $leaves = $query->paginate(5);

        return view('admin.pages.Leave.searchMyLeave', compact('leaves'));
    }

    public function leaveTypeSearch(Request $request)
    {
        $searchTerm = $request->search;

        $query = LeaveType::query(); // Use query builder instead of fetching all records

        if ($searchTerm) {
            $query->where('leave_type_id', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('leave_days', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('id', 'LIKE', '%' . $searchTerm . '%');
        }

        $leaveTypes = $query->paginate(5);
        $searchQuery = $searchTerm;

        return view('admin.pages.leaveType.formList', compact('leaveTypes', 'searchQuery'));
    }

    //Leave field Config

    public function leaveDocConfig()
    {
        $leaveFields = LeaveFields::paginate(10);
        // $documentType = DocumentTypes::all();

        $searchQuery = [];

        return view('admin.pages.Request.leaveConfig',compact('leaveFields','searchQuery'));
    }

    public function searchDocConfig(Request $request)
    {
        $searchTerm = $request->input('search');

        // Query to search in document fields and related document types
        $leaveFields = LeaveFields::with('typeConnection') // Load the relationship for document types
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->where('leave_fieldname', 'LIKE', '%' . $searchTerm . '%')
                             ->orWhereHas('typeConnection', function ($query) use ($searchTerm) {
                                 $query->where('leave_type_id', 'LIKE', '%' . $searchTerm . '%');
                             });
            })
            ->paginate(10);

            $searchQuery = $searchTerm;

        return view('admin.pages.Request.leaveConfig',compact('leaveFields','searchQuery'));
    }

    public function leaveDocForm($IsPreview =false)
    {
        $empData = Employee::all();
        $userAdmin = User::where('role', 'Admin')->get();
        $dep = Department::all();
        $configField = LeaveFields::all();
        $leaveType = LeaveTypes::all();

        $leaveAdmin = User::where('role', 'System Admin')->get();



        return view('admin.pages.Request.leaveDocument', compact('empData', 'userAdmin', 'dep', 'IsPreview','configField','leaveType','leaveAdmin'));

    }

    public function setVisibleInvisible($id){
        // Fetch the document field record
         $field = LeaveFields::findOrFail($id);

         // Toggle the visibility
         $field->update([
             'is_visible' => !$field->is_visible
         ]);

         return redirect()->back();

     }


     public function submitDocumentLeave(Request $request){

        // Validate the basic fields
        $validate = Validator::make($request->all(), [
            'SystemAdmin' => 'required'
        ]);

        // Check if at least one checkbox is selected
        $hasChecked = false;
        foreach ($request->except(['name', 'purposes']) as $key => $value) {
            if (str_starts_with($key, 'leave') && $value == 'on') {
                $hasChecked = true;
                break;
            }
        }

        // Handle validation failure
        if ($validate->fails() || !$hasChecked) {
            notify()->error('Please make sure to fill out all required fields and check at least one document.');
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $configField = LeaveFields::with('typeConnection')->get();



        // Collect checked document types
        $requestedLeaves = [];
        foreach ($request->all() as $key => $value) {
                if (str_starts_with($key, 'leave')) {
                    $fieldId = str_replace('leave', '', $key);
                    $documentField = $configField->where('id', $fieldId)->first();
                    if ($documentField->typeConnection->leave_days) {
                    $requestedLeaves[] = $documentField->typeConnection->leave_type_id . " (" . $documentField->typeConnection->leave_days . " days)";
                    }else{
                        $requestedLeaves[] = $documentField->typeConnection->leave_type_id;
                    }
                }
        }
    //dd( implode(', ', $requestedLeaves));

    $empData = Employee::where('name',auth()->user()->name)->first();

    $statusPending = Status::where('status_type','Pending')->first();

    if(!$statusPending){
        $statusPending = Status::create([
            'status_type'=>'Pending',
        ]);
    }

    // Create the new document request
    Leave::create([
        'employee_id' => $empData->id,
        'date_filed' => $request->date_filed,
        'date_leave' => $request->date_leave,
        'requestedLeaves' => implode(', ', $requestedLeaves), // Save as comma-separated string
        'illness' => $request->illness,
        'place' => $request->place,
        'statuses_id'=>$statusPending->id
    ]);

    $selectedSystemAdmin = User::where('email',$request->SystemAdmin)->first();


    $requestedLeaves = implode('<br>', $requestedLeaves); // Show as new line separated string

    $body = "Hello {$empData->name},<br> Good news! Your leave request for {$request->date_filed} has been send. Please wait for the update of {$selectedSystemAdmin->name}:<br>
    <b><i>{$requestedLeaves}</i></b><br>";
    $footer = "Thank you for your cooperation!<br>
    HR Team.<br><b><i>Olongapo City National High School</i></b>";

    Mail::to($empData->email)->send(new LeaveMail($body, $footer));
 
    //Mail for Designated Admin


    $bodyAdmin = "Dear HR Team,<br>
    {$empData->name} has submitted a leave request today {$request->date_filed} for the dates of {$request->date_leave}.<br> Please review the request and proceed with the necessary approval or rejection.<br>
    <b><i>{$requestedLeaves}</i></b><br>
    Click here to check the request: <a href='" . route('leave.leaveStatus') . "'>Leave Info</a>";

    $footer = "Thank you,<br>
HR Document Monitoring System.<br><b><i>Olongapo City National High School</i></b>";
    

    Mail::to($selectedSystemAdmin->email)->send(new LeaveMail($bodyAdmin, $footer));

    notify()->success('Leave request successfully created!');
    return redirect()->route('leave.documentForm',0); // Adjust this as necessary

    }

    public function processDocument($id, Request $request)
    {
        $actionType = $request->input('action_type');

        $document = Leave::with('employee')->findOrFail($id);

        $employee = $document->employee;

        $auth= auth()->user();

        $request['released_by'] =$auth->name;
        $request['received_by'] =$auth->name;

        // Ensure required fields are provided based on the action type
        $requiredFields = [
            'release' => 'released_by',
            'receive' => 'received_by',
            'confirm' => 'status_type',
        ];

        if (empty($request->{$requiredFields[$actionType]})) {
            notify()->error(ucfirst($requiredFields[$actionType]) . ' is required');
            return redirect()->route('leave.leaveStatus');
        }

      

        $empLeaveData=Leave::find($id);
        if($empLeaveData->employee->name == null){
            notify()->error('User not found or deleted');
            return redirect()->route('leave.leaveStatus');
        }

        $selectedSystemAdmin = User::where('name',$request->released_by)->first();

        if($actionType == 'release'){
            Leave::where('id',$id)->update([
                'released_by'=>$auth->name,
                'released_timestamp'=>now(),
                'statuses_id'=>999
            ]);

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$employee->name},<br>Your leave request has been <b>RELEASED</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                Mail::to($employee->email)->send(new LeaveMail($body, "<b><i>Olongapo City National High School</i></b>"));

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$auth->name},<br>Leave request with LVNO:{$id} has been <b>RELEASED</b>.<br>Click here to check the request: <a href='" . route('leave.leaveStatus') . "'>Leave Info</a>";
                Mail::to($auth->email)->send(new LeaveMail($body, "<b><i>Olongapo City National High School</i></b>"));

        }elseif($actionType == 'receive'){
            Leave::where('id',$id)->update([
                'received_by'=>$request->received_by,
                'received_timestamp'=>now(),
                'statuses_id'=>909
            ]);

            $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
            $body = "Hi {$employee->name},<br>Your leave request was received by: <b>{$request->received_by}</b> at <b>" . date('m-d-Y h:i a', strtotime(now())) . "</b>.";
            Mail::to($employee->email)->send(new LeaveMail($body, "<b><i>Olongapo City National High School</i></b>"));

            $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
            $body = "Hi {$auth->name},<br>Leave request with LVNO:{$id} has been <b>RECEIVED</b>.<br>Click here to check the request: <a href='" . route('leave.leaveStatus') . "'>Leave Info</a>";
            Mail::to($auth->email)->send(new LeaveMail($body, "<b><i>Olongapo City National High School</i></b>"));
        }else{
            $statusType = Status::find($request->status_type)->status_type;

            $document->update([
                'statuses_id' => $request->status_type,
            'released_by'=>null,
            'released_timestamp'=>null,
        ]);
                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$employee->name},<br>Your leave request has been updated to <b>{$statusType}</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                Mail::to($employee->email)->send(new LeaveMail($body, "<b><i>Olongapo City National High School</i></b>"));

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$auth->name},<br>Leave request with LVNO:{$id} has been <b>{$statusType}</b>.<br>Click here to check the request: <a href='" . route('leave.leaveStatus') . "'>Leave Info</a>";
                Mail::to($auth->email)->send(new LeaveMail($body, "<b><i>Olongapo City National High School</i></b>"));
        }
        notify()->success('Leave request successfully processed!');
        return redirect()->route('leave.leaveStatus');
    }

    public function rejectDocument($id, Request $request){
        Leave::where('id',$id)->update([
            'statuses_id'=> 0,
            'reject_reason'=>$request->reject_reason.' Rejected by: '.auth()->user()->name.' at '.date('m-d-Y h:i a', strtotime(now()))
        ]);

        $empLeaveData=Leave::find($id);
        if($empLeaveData->employee->name == null){
            notify()->error('User not found or deleted');
            return redirect()->route('leave.leaveStatus');
        }else{
            $body = "Hello {$empLeaveData->employee->name},<br> We regret to inform you that your leave request has been <b>REJECTED</b>. If you need further clarification or wish to submit a revised request, please contact HR.<br>
            Thank you,  <br>
            HR Team.";
                $footer = "Thank you for your cooperation!<br>
                HR Team.<br><b><i>Olongapo City National High School</i></b>";
            
                Mail::to($empLeaveData->employee->email)->send(new RejectLeaveMail($body, $footer));
        
                notify()->success('Leave request successfully rejected');
                return redirect()->route('leave.leaveStatus');
        }

      
    }

    public function searchFormLeaveList(Request $request)
    {
        $searchTerm = $request->search;

        $query = Leave::with(['employee', 'employee.department', 'status']); // Ensure relationships are loaded

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('employee', function ($employeeQuery) use ($searchTerm) {
                        $employeeQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                                      ->orWhere('employee_id', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('employee.department', function ($departmentQuery) use ($searchTerm) {
                        $departmentQuery->where('department_name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('status', function ($statusQuery) use ($searchTerm) {
                        $statusQuery->where('status_type', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('date_filed', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('date_leave', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('requestedLeaves', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('reject_reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $auth= auth()->user();

        $empData = Employee::where('name',$auth->name )->first();


        $leaves = $query->paginate(5);

        $searchQuery = $searchTerm;

        $deletedStatuses = [];

        foreach ($leaves as $document) {
            if ($document->statuses_id) {
                $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                if ($deletedStatus) {
                    $deletedStatuses[$document->id] = $deletedStatus->status_type;
                } else {
                    $deletedStatuses[$document->id] = null; // Handle case where there's no matching DeletedStatus
                }
            }
        }

          //filter for rejected
          $rejectedLeaves = $query->where('reject_reason','!=','null')->paginate(5);

          foreach ($rejectedLeaves as $document) {
              if ($document->statuses_id) {
                  $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                  if ($deletedStatus) {
                      $deletedStatuses[$document->id] = $deletedStatus->status_type;
                  } else {
                      $deletedStatuses[$document->id] = null; // Handle case where there's no matching DeletedStatus
                  }
              }
          }
  
          //filter for receiveDocs
          $receivedLeaves = $query->where('received_by','!=','null')->paginate(5);
  
          foreach ($receivedLeaves as $document) {
              if ($document->statuses_id) {
                  $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                  if ($deletedStatus) {
                      $deletedStatuses[$document->id] = $deletedStatus->status_type;
                  } else {
                      $deletedStatuses[$document->id] = null; // Handle case where there's no matching DeletedStatus
                  }
              }
          }

        $statusTypes = Status::all();

        $filteredUsers = User::where('role', 'System Admin')
        ->with(['employee.designation'])
        ->get();

        // Example of filtering users with a specific designation name
        $systemAdminUsers = $filteredUsers->filter(function ($user) {
            return $user->employee && $user->employee->designation && $user->employee->designation->designation_name === 'Leave';
        });


        return view('admin.pages.Leave.leaveList', compact('leaves','searchQuery','deletedStatuses','statusTypes','systemAdminUsers','receivedLeaves','rejectedLeaves'));
    }

    public function searchLeaveDashList(Request $request)
    {
        $searchTerm = $request->search;

        $query = Leave::with(['employee', 'employee.department', 'status']); // Ensure relationships are loaded

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('employee', function ($employeeQuery) use ($searchTerm) {
                        $employeeQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                                      ->orWhere('employee_id', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('employee.department', function ($departmentQuery) use ($searchTerm) {
                        $departmentQuery->where('department_name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('status', function ($statusQuery) use ($searchTerm) {
                        $statusQuery->where('status_type', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('date_filed', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('date_leave', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('illness', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('place', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_timestamp', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_timestamp', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('requestedLeaves', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('reject_reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $auth= auth()->user();

        $empData = Employee::where('name',$auth->name )->first();

        //Employee Document Request

            $leaves = Leave::where('employee_id',$empData->id)->get();


        $leaves = $query->paginate(5);

        $searchQueryLeave = $searchTerm;

        $deletedStatuses = [];

        foreach ($leaves as $document) {
            if ($document->statuses_id) {
                $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                if ($deletedStatus) {
                    $deletedStatuses[$document->id] = $deletedStatus->status_type;
                } else {
                    $deletedStatuses[$document->id] = null; // Handle case where there's no matching DeletedStatus
                }
            }
        }

        //default leave
        $documentRequest = Documents::where('employee_id',$empData->id)->paginate(5);
        $searchQuery = null;


        return view('admin.pages.dashboardEmployee', compact('documentRequest','searchQuery','deletedStatuses','leaves','searchQueryLeave'));
    }

    public function destroy($leaveId)
    {
        // Find the leave request by ID
        $leave = Leave::findOrFail($leaveId);
    
        // Delete the leave request
        $leave->delete();
    
        // Optionally, add a success notification
        notify()->success('Leave Request Deleted Successfully.');
    
        // Redirect back to the leave request list
        return redirect()->back();
    }
}

