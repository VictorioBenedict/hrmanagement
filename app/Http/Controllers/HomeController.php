<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\DeletedStatus;
use App\Models\Department;
use App\Models\Designation;
use App\Models\IncomingFields;
use App\Models\Documents;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function home()
    {
        $employees = Employee::count();
        $departments = Department::count();
        $documents = Documents::count();
        $designation = Designation::count();

        $pendingLeaves = 0; 

        $user = auth()->user();

        if ($user && $user->role === 'Admin') {
            $totalLeaves = Leave::count();
            $approvedLeaves = 0;
            $rejectedLeaves = 0;
            $pendingLeaves = $totalLeaves - ($approvedLeaves + $rejectedLeaves);
        } else {
            $userId = $user ? $user->id : null;
            $totalLeaves = Leave::where('employee_id', $userId)->count();
            $approvedLeaves =0;
            $rejectedLeaves = 0;
            $pendingLeaves = $totalLeaves - ($approvedLeaves + $rejectedLeaves);
        }

        $users = User::count();
        $completedOnTimeTasks = Task::where('status', 'completed on time')->count();
        $completedInLateTasks = Task::where('status', 'completed in late')->count();
        $totalCompletedTasks = $completedOnTimeTasks + $completedInLateTasks;

        $totalTasks = Task::count() - $totalCompletedTasks;

        $auth= auth()->user();

        $empData = Employee::where('name',$auth->name)->first();

        $deletedStatusesLeave = [];

        $deletedStatusesDocument = [];

        if($auth->role == 'Employee'){
            $documents = Documents::paginate(5);
            $documentRequest = Documents::where('employee_id',$empData->id)->paginate(5);
            $leaves = Leave::where('employee_id',$empData->id)->paginate(5);
            foreach ($leaves as $document) {
                if ($document->statuses_id) {
                    $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                    if ($deletedStatus) {
                        $deletedStatusesLeave[$document->id] = $deletedStatus->status_type;
                    } else {
                        $deletedStatusesLeave[$document->id] = null; 
                    }
                }
            }

            foreach ($documentRequest as $document) {
                if ($document->statuses_id) {
                    $deletedStatus = DeletedStatus::where('statuses_id', $document->statuses_id)->first();
                    if ($deletedStatus) {
                        $deletedStatusesDocument[$document->id] = $deletedStatus->status_type;
                    } else {
                        $deletedStatusesDocument[$document->id] = null; 
                    }
                }
            }
        }else{
            $documentRequest = [];
            $leaves = [];
        }


    $searchQuery = null;
    $searchQueryLeave = null;
    
    $incoming = IncomingFields::paginate(5);

        return view('admin.pages.dashboard', compact('employees', 'departments', 'pendingLeaves', 'users', 'totalTasks','documentRequest','designation','documents','leaves','searchQuery','searchQueryLeave','deletedStatusesLeave','deletedStatusesDocument','incoming'));
    }



    public function showHeader()
    {
        // Fetch the logged-in user
        $user = auth()->user();

        return view('admin.partials.header', compact('user'));
    }


    // contact message
    public function message()
    {
        $messages = Contact::all();
        return view('admin.pages.contactMessage.contactMessage', compact('messages'));
    }
}
