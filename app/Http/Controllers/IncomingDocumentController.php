<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use App\Mail\IncomingMail;
use App\Mail\RejectLeaveMail;
use App\Models\IncomingDocuments;
use App\Models\DeletedStatus;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\ActionType;
use App\Models\Status;
use App\Models\IncomingFields;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class IncomingDocumentController extends Controller
{

    public function incoming()
    {
        $incoming = IncomingDocuments::orderBy('id','desc')->all();
        $actionTypes = ActionType::orderBy('id','desc')->all();

        return view('admin.pages.Leave.leaveForm', compact('incoming', 'actionTypes'));
    }
    public function incomingList()
    {
        $incomingDocuments = IncomingDocuments::paginate(5);
       
        return view('admin.pages.IncomingDocuments.incomingList', compact('incomingDocuments'));
           
    }

    public function updatestatus(Request $request, $id){
        $document = IncomingDocuments::findOrFail($id);
        $document -> status = $request ->input('status');
        $document -> save();
        notify()->success('Document updated successfully!');
        return redirect()->back()->with('success', 'Document updated successfully!');
    }

    public function delete($id){
        $document = IncomingDocuments::findOrFail($id);
        $document -> delete();
        notify()->success('Document deleted successfully!');
        return redirect()->back()->with('success', 'Document deleted successfully!');
    }

    public function incomingSearch(Request $request)
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

    public function incomingConfig(){
        $incomingFields = IncomingFields::paginate(10);

        $searchQuery = [];

        return view('admin.pages.Request.incomingConfig',compact('incomingFields','searchQuery'));
    }

    public function setVisibleInvisible($id){
        // Fetch the document field record
         $field = IncomingFields::findOrFail($id);

         // Toggle the visibility
         $field->update([
             'is_visible' => !$field->is_visible
         ]);

         return redirect()->back();

     }

     public function searchDocConfig(Request $request)
     {
         $searchTerm = $request->input('search');
 
         // Query to search in document fields and related document types
         $incomingFields = IncomingFields::with('typeConnection') // Load the relationship for document types
             ->when($searchTerm, function ($query, $searchTerm) {
                 return $query->where('incoming_fieldname', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhereHas('typeConnection', function ($query) use ($searchTerm) {
                                  $query->where('action_type_id', 'LIKE', '%' . $searchTerm . '%');
                              });
             })
             ->paginate(10);
 
             $searchQuery = $searchTerm;
 
         return view('admin.pages.Request.incomingConfig',compact('incomingFields','searchQuery'));
     }

     public function incomingDocForm()
     {  
        $user = auth()->user();
        $employee = $user->employee; 
        $empno = $employee ? $employee->employee_id : 'Employee not found';
        $departmentName = $employee && $employee->department ? $employee->department->department_name : 'No department assigned';

        $incomingFields = IncomingFields::paginate(5);
        $systemAdmins = User::where('role', 'admin')->get();

    
        return view('admin.pages.Request.incomingDocumentForm', compact('empno', 'incomingFields','departmentName', 'systemAdmins'));

 
     }

     public function actionList(){
        $actionTypes = ActionType::paginate(5);
        $searchQuery = [];
        return view('admin.pages.actionType.formList', compact('actionTypes','searchQuery'));
     }

     
     public function storeAction(Request $request){
       // dd($request->all());

            $validate = Validator::make($request->all(), [
                'action_type_id' => 'required|string|unique:action_types,action_type_id',
            ]);

            if ($validate->fails()) {
                notify()->error($validate->getMessageBag());
                return redirect()->back();
            }

         $actionType = ActionType::create([
                'action_type_id' => $request->action_type_id
            ]);

            IncomingFields::create([
                'incoming_fieldname'=>$request->action_type_id,
                'action_type_id'=>$actionType->id,
                'is_visible'=>1
            ]);

            notify()->success('New Action Type created successfully.');
            return redirect()->back();
     }

     public function actionDelete($id)
     {
         $actionType = ActionType::find($id);

         if ($actionType) {
             $actionType->delete();
             IncomingFields::where('action_type_id',$id)->delete();
         }
         notify()->success('Deleted Successfully.');
         return redirect()->back();
     }
     public function actionEdit($id)
     {
         $actionType = ActionType::find($id);
         return view('admin.pages.actionType.editList', compact('actionType'));
     }
     public function actionUpdate(Request $request, $id)
     {
 
          // Validate the request
          $validate = Validator::make($request->all(), [
             'action_type_id' => [
                 'required',
                 Rule::unique('action_types', 'action_type_id')->ignore($id),
             ],
         ]);
 
         // Handle validation failure
         if ($validate->fails()) {
             notify()->error($validate->getMessageBag()->first());
             return redirect()->back()->withErrors($validate)->withInput();
         }
 
 
         $actionType = ActionType::find($id);
         if ($actionType) {
 
             $actionType->update([
                 'action_type_id' => $request->action_type_id
             ]);
 
             notify()->success('Your information updated successfully.');
             return redirect()->route('incoming.action.list');
         }
     }

     public function sendDocumentCopy(Request $request){
            // Validate basic fields
            $validate = Validator::make($request->all(), [
                'remarks' => 'required|string|max:255',
                'SystemAdmin' => 'required',
            ]);
            
            if ($validate->fails()) {
                notify()->error('Please fill out all required fields.');
                return redirect()->back()->withErrors($validate)->withInput();
            }
            
            // Check if at least one "incoming" checkbox is selected
            $checkedFields = array_filter($request->all(), function ($key) {
                return str_starts_with($key, 'incoming');
            }, ARRAY_FILTER_USE_KEY);
            
            if (empty($checkedFields)) {
                notify()->error('Please select at least one action.');
                return redirect()->back()->withInput();
            }
            
          
            $requestedDocs = [];
            $configField = IncomingFields::with('typeConnection')->get();
            foreach ($checkedFields as $key => $value) {
                $fieldId = str_replace('incoming', '', $key);
                $documentField = $configField->where('id', $fieldId)->first();
                if ($documentField) {
                    $requestedDocs[] = $documentField->typeConnection->action_type_id;
                }
            }
            
            if (empty($requestedDocs)) {
                notify()->error('Selected actions are invalid.');
                return redirect()->back()->withInput();
            }
            
            $empData = Employee::where('name', auth()->user()->name)->first();
            $statusPending = Status::firstOrCreate(['status_type' => 'pending']);
                 
            $departmentName = $empData->department ? $empData->department->department_name : 'No department assigned';
            $empno = $empData->employee_id ?? 'No employee number'; 
               
            IncomingDocuments::create([
                'employee_id' => $empData->id,
                'empno' => $empno,  
                'name' => $empData->name,
                'department' => $departmentName,  
                'status' => 'pending',
                'date' => now(),
                'remarks' => $request->remarks,
                'actions_id' => implode(', ', $requestedDocs),
            ]);
            
            notify()->success('Incoming Document Copy successfully sent to admin!');
            return redirect()->route('incoming.documentForm', ['IsPreview' => 0]);
            
            
        
     }

     public function rejectDocumentCopy(Request $request, $id){
        IncomingDocuments::where('id',$id)->update([
            'statuses_id'=> 0,
            'reject_reason'=>$request->reject_reason.' Rejected by: '.auth()->user()->name.' at '.date('m-d-Y h:i a', strtotime(now()))
        ]);

        $empLeaveData=IncomingDocuments::find($id);
        if($empLeaveData->employee->name == null){
            notify()->error('User not found or deleted');
            return redirect()->route('leave.leaveStatus');
        }else{
            $body = "Hello {$empLeaveData->employee->name},<br> We regret to inform you that your Incoming Document Copy request has been <b>REJECTED</b>. If you need further clarification or wish to submit a revised request, please contact HR.<br>
            Thank you,  <br>
            HR Team.";
                $footer = "Thank you for your cooperation!<br>
                HR Team.<br><b><i>Olongapo City National High School</i></b>";
            
                Mail::to($empLeaveData->employee->email)->send(new RejectLeaveMail($body, $footer));
        
                notify()->success('Leave request successfully rejected');
                return redirect()->route('incoming.list');
        }
     }

     public function incomingdocsSearch(Request $request){
        $searchTerm = $request->search;

        $query = IncomingDocuments::with(['employee', 'employee.department', 'status','action']); 

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
                    ->orWhereHas('action', function ($statusQuery) use ($searchTerm) {
                        $statusQuery->where('actions_id', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('remarks', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('released_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('received_by', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('reject_reason', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $auth= auth()->user();

        $empData = Employee::where('name',$auth->name )->first();


        $incoming = $query->paginate(5);

        $searchQuery = $searchTerm;

        $deletedStatuses = [];

        foreach ($incoming as $document) {
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


        return view('admin.pages.IncomingDocuments.incomingList', compact('incoming','searchQuery','deletedStatuses','statusTypes','systemAdminUsers','receivedLeaves','rejectedLeaves'));
     }


     public function processDocument($id, Request $request)
     {
         $actionType = $request->input('action_type');
 
         $document = IncomingDocuments::with('employee')->findOrFail($id);
 
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
             return redirect()->route('incoming.list');
         }
 
       
 
         $empLeaveData=IncomingDocuments::find($id);
         if($empLeaveData->employee->name == null){
             notify()->error('User not found or deleted');
             return redirect()->route('incoming.list');
         }
 
         $selectedSystemAdmin = User::where('name',$request->released_by)->first();
 
         if($actionType == 'release'){
             IncomingDocuments::where('id',$id)->update([
                 'released_by'=>$auth->name,
                 'released_timestamp'=>now(),
                 'statuses_id'=>999
             ]);
 
                 $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                 $body = "Hi {$employee->name},<br>Your Incoming Document Copy has been <b>RELEASED</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                 Mail::to($employee->email)->send(new IncomingMail($body, "<b><i>Olongapo City National High School</i></b>"));
 
                 $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                 $body = "Hi {$auth->name},<br>Incoming Document Copy with LVNO:{$id} has been <b>RELEASED</b>.<br>Click here to check the request: <a href='" . route('incoming.list') . "'>Leave Info</a>";
                 Mail::to($auth->email)->send(new IncomingMail($body, "<b><i>Olongapo City National High School</i></b>"));
 
         }elseif($actionType == 'receive'){
             IncomingDocuments::where('id',$id)->update([
                 'received_by'=>$request->received_by,
                 'received_timestamp'=>now(),
                 'statuses_id'=>909
             ]);
 
             $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
             $body = "Hi {$employee->name},<br>Your Incoming Document Copy was received by: <b>{$request->received_by}</b> at <b>" . date('m-d-Y h:i a', strtotime(now())) . "</b>.";
             Mail::to($employee->email)->send(new IncomingMail($body, "<b><i>Olongapo City National High School</i></b>"));
 
             $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
             $body = "Hi {$auth->name},<br>Incoming Document Copy with CTRLNO:{$id} has been <b>RECEIVED</b>.<br>Click here to check the request: <a href='" . route('incoming.list') . "'>Leave Info</a>";
             Mail::to($auth->email)->send(new IncomingMail($body, "<b><i>Olongapo City National High School</i></b>"));
         }else{
             $statusType = Status::find($request->status_type)->status_type;
 
             $document->update([
                 'statuses_id' => $request->status_type,
             'released_by'=>null,
             'released_timestamp'=>null,
         ]);
                 $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                 $body = "Hi {$employee->name},<br>Your Incoming Document Copy has been updated to <b>{$statusType}</b>.<br>Please bring the following:<br><b><i>{$bringRequirements}</i></b>";
                 Mail::to($employee->email)->send(new IncomingMail($body, "<b><i>Olongapo City National High School</i></b>"));
 
                 $bringRequirements = $request->bringRequirements ? "<b><i>{$request->bringRequirements}</i></b>" : 'N/A';
                 $body = "Hi {$auth->name},<br>Incoming Document Copy with LVNO:{$id} has been <b>{$statusType}</b>.<br>Click here to check the request: <a href='" . route('incoming.list') . "'>Leave Info</a>";
                 Mail::to($auth->email)->send(new IncomingMail($body, "<b><i>Olongapo City National High School</i></b>"));
         }
         notify()->success('Incoming Document Copy successfully processed!');
         return redirect()->route('incoming.list');
     }



     public function incomingSearchDash(Request $request)
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
     

}
