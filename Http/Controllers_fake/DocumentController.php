<?php

namespace App\Http\Controllers;

use App\Mail\DocumentMail;
use App\Models\Department;
use App\Models\Designation;
use App\Models\DocumentFields;
use App\Models\Documents;
use App\Models\DocumentTypes;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Task;
use App\Models\User;
use App\Models\Status;
use App\Models\DeletedStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use League\CommonMark\Node\Block\Document;

class DocumentController extends Controller
{
    public function documentType()
    {
        $documentTypes = DocumentTypes::paginate(5);
        $searchQuery = [];
        return view('admin.pages.Document.formList', compact('documentTypes','searchQuery'));
    }

    public function documentTypeSearch(Request $request)
    {
        $searchTerm = $request->search;

        $query = DocumentTypes::query(); // Use query builder instead of fetching all records

        if ($searchTerm) {
            $query->where('document_type', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('id', 'LIKE', '%' . $searchTerm . '%');
        }

        $documentTypes = $query->paginate(5);
        $searchQuery = $searchTerm;

        return view('admin.pages.Document.formList', compact('documentTypes', 'searchQuery'));
    }


    public function documentRequest()
    {
        $documents = Documents::paginate(5);
        $searchQuery = null;

        $deletedStatuses = [];

        foreach ($documents as $document) {
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
            return $user->employee && $user->employee->designation && $user->employee->designation->designation_name === 'Document';
        });


        return view('admin.pages.Document.documentList', compact('documents','searchQuery','deletedStatuses','statusTypes','systemAdminUsers'));
    }

    public function documentStore(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'document_type' => 'required|unique:document_types,document_type',
        ]);

        if ($validate->fails()) {
            notify()->error($validate->getMessageBag());
            return redirect()->back();
        }


        $docField = DocumentTypes::create([
            'document_type'=>$request->document_type
        ]);

        DocumentFields::create([
            'document_fieldname'=>$request->document_type,
            'document_type_id'=>$docField->id,
            'is_visible'=>1
          ]);



        notify()->success('New Document created');
        return redirect()->back();
    }


    public function documentEdit($Id){
        $documentTypes = DocumentTypes::find($Id);
        return view('admin.pages.Document.editList', compact('documentTypes'));
    }

    public function documentUpdate(Request $request, $id)
    {
        // Validate the request
        $validate = Validator::make($request->all(), [
            'document_type' => [
                'required',
                Rule::unique('document_types', 'document_type')->ignore($id),
            ],
        ]);

        // Handle validation failure
        if ($validate->fails()) {
            notify()->error($validate->getMessageBag()->first());
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Update the document type
        $document = DocumentTypes::find($id);
        if ($document) {
            $document->document_type = $request->document_type;
            $document->save();

            notify()->success('Document type updated successfully!');
        } else {
            notify()->error('Document type not found!');
        }

        return redirect()->route('document.documentType');
    }



    public function documentDelete($Id)
    {
        DocumentFields::where('document_type_id', $Id)->delete();

        DocumentTypes::where('id', $Id)->delete();

        notify()->success('Document deleted successfully!');
        return redirect()->back();
    }

    public function submitDocumentRequest(Request $request){

        // Validate the basic fields
        $validate = Validator::make($request->all(), [
            // 'name' => 'required',
            // 'purposes' => 'required'
        ]);

        // Check if at least one checkbox is selected
        $hasChecked = false;
        foreach ($request->except(['name', 'purposes']) as $key => $value) {
            if (str_starts_with($key, 'doc') && $value == 'on') {
                $hasChecked = true;
                break;
            }
        }

        // Handle validation failure
        if ($validate->fails() || !$hasChecked) {
            notify()->error('Please make sure to fill out all required fields and check at least one document.');
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $configField = DocumentFields::with('typeConnection')->get();



        // Collect checked document types
        $requestedDocs = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'doc')) {
                $fieldId = str_replace('doc', '', $key);
                $documentField = $configField->where('id', $fieldId)->first();
                if ($documentField) {
                    $requestedDocs[] = $documentField->typeConnection->document_type;
                }
            }
    }

    //dd( implode(', ', $requestedDocs));

    $empData = Employee::where('name',$request->name)->first();

    $statusPending = Status::where('status_type','Pending')->first();

    if(!$statusPending){
        $statusPending = Status::create([
            'status_type'=>'Pending',
        ]);
    }

    // Create the new document request
    Documents::create([
        'employee_id' => $empData->id,
        'date' => $request->date,
        'purposes' => $request->purposes,
        'requestedDocs' => implode(', ', $requestedDocs), // Save as comma-separated string
        'statuses_id'=>$statusPending->id
    ]);

    $requestedDocs = implode('<br>', $requestedDocs); // Show as new line separated string

    $body = "Hi {$empData->name},<br>We received your request. Kindly give us atleast 2-5 working days to process this following:<br>
    <b><i>{$requestedDocs}</i></b><br>";
    $footer = "<b><i>Olongapo City National High School</i></b>";

    Mail::to($empData->email)->send(new DocumentMail($body, $footer));

    notify()->success('Document request successfully created!');
    return redirect()->route('request.documentForm',0); // Adjust this as necessary

    }

    public function rejectDocument($id, Request $request){
        Documents::where('id',$id)->update([
            'statuses_id'=> 0,
            'reject_reason'=>$request->reject_reason.' Rejected by: '.auth()->user()->name.' at '.date('m-d-Y h:i a', strtotime(now()))
        ]);

        notify()->success('Document request successfully rejected');
        return redirect()->route('document.documentStatus');
    }

    public function processDocument($id, Request $request)
    {
        $actionType = $request->input('action_type');

        $document = Documents::with('employee')->findOrFail($id);

        $employee = $document->employee;
        // Ensure required fields are provided based on the action type
        $requiredFields = [
            'release' => 'released_by',
            'receive' => 'received_by',
            'confirm' => 'status_type',
        ];

        if (empty($request->{$requiredFields[$actionType]})) {
            notify()->error(ucfirst($requiredFields[$actionType]) . ' is required');
            return redirect()->route('document.documentStatus');
        }

        if($actionType == 'release'){
            Documents::where('id',$id)->update([
                'released_by'=>$request->released_by,
                'released_timestamp'=>now(),
                'statuses_id'=>999
            ]);

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$employee->name},<br>Your request document has been <b>RELEASED</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                Mail::to($employee->email)->send(new DocumentMail($body, "<b><i>Olongapo City National High School</i></b>"));

        }elseif($actionType == 'receive'){
            Documents::where('id',$id)->update([
                'received_by'=>$request->received_by,
                'received_timestamp'=>now(),
                'statuses_id'=>909
            ]);

            $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
            $body = "Hi {$employee->name},<br>Your request document received by:<b>{$request->received_by}</b> at {{ date('m-d-Y h:i a', strtotime(now())) }}.";
            Mail::to($employee->email)->send(new DocumentMail($body, "<b><i>Olongapo City National High School</i></b>"));
        }else{

            $statusType = Status::find($request->status_type)->status_type;

            $document->update(['statuses_id' => $request->status_type]);
                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$employee->name},<br>Your request document has been updated to <b>{$statusType}</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                Mail::to($employee->email)->send(new DocumentMail($body, "<b><i>Olongapo City National High School</i></b>"));
        }
        notify()->success('Document request successfully processed!');
        return redirect()->route('document.documentStatus');
    }





    public function searchDocumentList(Request $request)
    {
        $searchTerm = $request->search;

        $query = Documents::with(['employee', 'employee.department', 'status']); // Ensure relationships are loaded

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
                    ->orWhere('date', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('requestedDocs', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('purposes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_timestamp', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_timestamp', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('reject_reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $auth= auth()->user();

        $empData = Employee::where('name',$auth->name )->first();

        //Employee Document Request

            $documentRequest = Documents::where('employee_id',$empData->id)->get();


        $documentRequest = $query->paginate(5);

        $searchQuery = $searchTerm;

        $deletedStatuses = [];

        foreach ($documentRequest as $document) {
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
        $leaves = Leave::where('employee_id',$empData->id)->paginate(5);
        $searchQueryLeave = null;


        return view('admin.pages.dashboardEmployee', compact('documentRequest','searchQuery','deletedStatuses','leaves','searchQueryLeave'));
    }

    public function searchFormDocumentList(Request $request)
    {
        $searchTerm = $request->search;

        $query = Documents::with(['employee', 'employee.department', 'status']); // Ensure relationships are loaded

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
                    ->orWhere('date', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('requestedDocs', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('purposes', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('reject_reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $auth= auth()->user();

        $empData = Employee::where('name',$auth->name )->first();


        $documents = $query->paginate(5);

        $searchQuery = $searchTerm;

        $deletedStatuses = [];

        foreach ($documents as $document) {
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



        return view('admin.pages.Document.documentList', compact('documents','searchQuery','deletedStatuses','statusTypes','systemAdminUsers'));
    }


}
