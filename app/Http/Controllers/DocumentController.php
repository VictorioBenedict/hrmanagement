<?php

namespace App\Http\Controllers;

use App\Mail\DocumentMail;
use App\Mail\RejectDocumentMail;
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
        $documentTypes = DocumentTypes::orderBy('id','desc')->paginate(5);

        return view('admin.pages.Document.formList', compact('documentTypes'));
    }

    public function documentTypeSearch(Request $request)
    {
        $searchTerm = $request->search;

        $query = DocumentTypes::query(); // Use query builder instead of fetching all records

        if ($searchTerm) {
            $query->where('document_type', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('id', 'LIKE', '%' . $searchTerm . '%');
        }

        $documentTypes = $query->orderBy('id','desc')->paginate(5);
        $searchQuery = $searchTerm;

        return view('admin.pages.Document.formList', compact('documentTypes', 'searchQuery'));
    }


    public function documentRequest()
    {
        $documents = Documents::whereNotIn('statuses_id', [0, 999])->orderBy('id','desc')->paginate(5);
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

        $rejectedDocument = Documents::with(['employee.department'])
        ->where('statuses_id', 0)
        ->orderBy('id','desc') // Fetch rejected leaves
        ->get();

        $receivedDocument= Documents::with(['employee.department'])
        ->where('statuses_id', 999)
        ->orderBy('id','desc') // Fetch received leaves
        ->get();
        $statusTypes = Status::all();

        $filteredUsers = User::where('role', 'System Admin')
        ->with(['employee.designation'])
        ->get();

        // Example of filtering users with a specific designation name
        $systemAdminUsers = $filteredUsers->filter(function ($user) {
            return $user->employee = 'user';
        });

        $screenTitle = "Document Request";

        return view('admin.pages.Document.documentList', compact('documents', 'rejectedDocument', 'receivedDocument','searchQuery','deletedStatuses','statusTypes','systemAdminUsers','screenTitle'));
    }

    public function documentArchive()
    {
        $documents = Documents::where('status','archive')->paginate(5);
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

         $screenTitle = "Deleted Document";

        return view('admin.pages.Document.documentList', compact('documents','searchQuery','deletedStatuses','statusTypes','systemAdminUsers', 'screenTitle'));
    }

    public function documentList()
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


        return view('admin.pages.Document.documents', compact('documents','searchQuery','deletedStatuses','statusTypes','systemAdminUsers'));
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
            'SystemAdmin' => 'required'
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

        // Check if the user has already made two document requests
        $empData = Employee::where('name', auth()->user()->name)->first();
        $requestCount = Documents::where('employee_id', $empData->id)->count();

        if ($requestCount >= 2) {
            notify()->error('You can only request documents twice.');
            return redirect()->back();
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

        // Create or retrieve 'Pending' status
        $statusPending = Status::where('status_type', 'Pending')->first();
        if (!$statusPending) {
            $statusPending = Status::create([
                'status_type' => 'Pending',
            ]);
        }

        // Create the new document request
        Documents::create([
            'employee_id' => $empData->id,
            'date' => $request->date,
            'purposes' => $request->purposes,
            'requestedDocs' => implode(', ', $requestedDocs), // Save as comma-separated string
            'statuses_id' => $statusPending->id
        ]);

        $requestedDocs = implode('<br>', $requestedDocs); // Show as new line separated string

        // Send email to employee
        $body = "Hi {$empData->name},<br>We received your request. Kindly give us at least 2-5 working days to process the following:<br>
        <b><i>{$requestedDocs}</i></b><br>";
        $footer = "<b><i>Olongapo City National High School</i></b>";

        Mail::to($empData->email)->send(new DocumentMail($body, $footer));

        // Send email to designated admin
        $selectedSystemAdmin = User::where('email', $request->SystemAdmin)->first();
        $bodyAdmin = "Dear HR Team,
        {$empData->name} has submitted a request for the document:<br>
        <b><i>{$requestedDocs}</i></b><br>Please review the request and proceed with the necessary receive or rejection.<br>
        <b><i>{$requestedDocs}</i></b><br>
        Click here to check the request: <a href='" . route('document.documentStatus') . "'>Document Info</a>";
        $footer = "Thank you,<br>
        HR Document Monitoring System.<br><b><i>Olongapo City National High School</i></b>";

        Mail::to($selectedSystemAdmin->email)->send(new DocumentMail($bodyAdmin, $footer));

        notify()->success('Document request successfully created!');
        return redirect()->route('request.documentForm', 0); // Adjust this as necessary


    }

    public function rejectDocument($id, Request $request){
        Documents::where('id',$id)->update([
            'statuses_id'=> 0,
            'reject_reason'=>$request->reject_reason.' Rejected by: '.auth()->user()->name.' at '.date('m-d-Y h:i a', strtotime(now()))
        ]);

        $empDocsData=Documents::find($id);

        if($empDocsData->employee->email  == null){
            notify()->error('User not found or deleted');
            return redirect()->route('document.documentStatus');
        }

        //$empData = Employee::where('id',$empDocsData->employee_id)->first();

        $body = "Hello {$empDocsData->employee->name},<br> We regret to inform you that your document request has been <b>REJECTED</b>. If you need further clarification or wish to submit a revised request, please contact HR or try to request again.<br>
        Thank you,  <br>
        HR Team.";
            $footer = "Thank you for your cooperation!<br>
            HR Team.<br><b><i>Olongapo City National High School</i></b>";
        
            Mail::to($empDocsData->employee->email)->send(new RejectDocumentMail($body, $footer));
    

        notify()->success('Document request successfully rejected');
        return redirect()->route('document.documentList');
    }

    public function processDocument($id, Request $request)
    {
        $actionType = $request->input('action_type');

        $document = Documents::with('employee')->findOrFail($id);

        $employee = $document->employee;

        $auth=auth()->user();

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
            return redirect()->route('document.documentStatus');
        }


        $empDocsData=Documents::find($id);

        if($empDocsData->employee->email  == null){
            notify()->error('User not found or deleted');
            return redirect()->route('document.documentStatus');
        }

        if($actionType == 'release'){
            Documents::where('id',$id)->update([
                'statuses_id'=>999,
                'released_by'=>$auth->name,
                'released_timestamp'=>now(),
            ]);

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$employee->name},<br>Your request document has been <b>RELEASED</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                Mail::to($employee->email)->send(new DocumentMail($body, "<b><i>Olongapo City National High School</i></b>"));


                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $bodyAdmin = "Hi {$auth->name},<br>Request document with RQNO:{$id} has been <b>RELEASED</b>.<br> Click here to check the request: <a href='" . route('document.documentStatus') . "'>Document Info</a>";
                Mail::to($auth->email)->send(new DocumentMail($bodyAdmin, "<b><i>Olongapo City National High School</i></b>"));

        }elseif($actionType == 'receive'){
            Documents::where('id',$id)->update([
                'received_by'=>$auth->name,
                'received_timestamp'=>now(),
                'statuses_id'=>909
            ]);

            $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
            $body = "Hi {$employee->name},<br>Your request document received by:<b>{$auth->name}</b> at {{ date('m-d-Y h:i a', strtotime(now())) }}.";
            Mail::to($employee->email)->send(new DocumentMail($body, "<b><i>Olongapo City National High School</i></b>"));

            $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
            $bodyAdmin = "Hi {$auth->name},<br>Request document with RQNO:{$id} has been <b>RECEIVED</b>.<br> Click here to check the request: <a href='" . route('document.documentStatus') . "'>Document Info</a>";
            Mail::to($auth->email)->send(new DocumentMail($bodyAdmin, "<b><i>Olongapo City National High School</i></b>"));
        }else{

            $statusType = Status::find($request->status_type)->status_type;

            

            Documents::where('id',$id)->update([
            'statuses_id' => $request->status_type,
            'released_by'=>null,
            'released_timestamp'=>null,]);

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $body = "Hi {$employee->name},<br>Your request document has been updated to <b>{$statusType}</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                Mail::to($employee->email)->send(new DocumentMail($body, "<b><i>Olongapo City National High School</i></b>"));

                $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                $bodyAdmin = "Hi {$auth->name},<br>Request document with RQNO:{$id} has been <b>{$statusType}</b>.<br> Click here to check the request: <a href='" . route('document.documentStatus') . "'>Document Info</a>";
                Mail::to($auth->email)->send(new DocumentMail($bodyAdmin, "<b><i>Olongapo City National High School</i></b>"));
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

        //default filter
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

        //filter for rejected
        $rejectedDocument = $query->where('reject_reason','!=','null')->paginate(5);

        foreach ($rejectedDocument as $document) {
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
        $receiveDocument = $query->where('received_by','!=','null')->paginate(5);

        foreach ($receiveDocument as $document) {
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

      


        return view('admin.pages.dashboardEmployee', compact('documentRequest','searchQuery','deletedStatuses','leaves','searchQueryLeave','rejectedDocument','receiveDocument'));
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

        $documentRequest = $query->paginate(5);

         //default filter
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

        //filter for rejected
        $rejectedDocument = $query->where('reject_reason','!=','null')->paginate(5);

        foreach ($rejectedDocument as $document) {
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
        $receivedDocument = $query->where('received_by','!=','null')->paginate(5);

        foreach ($receivedDocument as $document) {
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



        return view('admin.pages.Document.documentList', compact('documents','searchQuery','deletedStatuses','statusTypes','systemAdminUsers','documentRequest','rejectedDocument','receivedDocument'));
    }


}