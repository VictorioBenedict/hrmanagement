<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DocumentFields;
use App\Models\DocumentTypes;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{

    public function requestDocForm($IsPreview =false)
    {
        $empData = Employee::all();

        // //Employee only for emp user
        // if(auth()->user()->role == 'Employee'){
        //     // Retrieve all users with the 'Employee' role
        //     $userEmp = User::where('role', 'Employee')->get();

        //     // Initialize an array to hold the employee data
        //     $empData = [];

        //     // Loop through the users and find the corresponding Employee data
        //     foreach($userEmp as $user){
        //         $employee = Employee::where('email', $user->email)->first();
        //         if($employee) {
        //             $empData[] = $employee;
        //         }
        //    }
        // }

        $userAdmin = User::where('role', 'Admin')->get();
        $dep = Department::all();
        $configField = DocumentFields::all();
        $documentType = DocumentTypes::all();
        if(auth()->user()->role == 'Admin'){
            $employeeDepartment = Department::all();
        }else{
            $employeeDepartment = Employee::where('name', Auth::user()->name)
            ->first()
            ->department
            ->department_name;
        }



        return view('admin.pages.Request.requestDocument', compact('empData', 'userAdmin', 'dep', 'IsPreview','configField','documentType','employeeDepartment'));

    }


    public function requestDocConfig()
    {
        $docFields = DocumentFields::paginate(15);
        // $documentType = DocumentTypes::all();

        $searchQuery = [];

        return view('admin.pages.Request.requestConfig',compact('docFields','searchQuery'));
    }

    public function searchDocConfig(Request $request)
    {
        $searchTerm = $request->input('search');

        // Query to search in document fields and related document types
        $docFields = DocumentFields::with('typeConnection') // Load the relationship for document types
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->where('document_fieldname', 'LIKE', '%' . $searchTerm . '%')
                             ->orWhereHas('typeConnection', function ($query) use ($searchTerm) {
                                 $query->where('document_type', 'LIKE', '%' . $searchTerm . '%');
                             });
            })
            ->paginate(10);

            $searchQuery = $searchTerm;

        return view('admin.pages.Request.requestConfig', compact('docFields', 'searchQuery'));
    }


    public function setVisibleInvisible($id){
       // Fetch the document field record
        $field = DocumentFields::findOrFail($id);

        // Toggle the visibility
        $field->update([
            'is_visible' => !$field->is_visible
        ]);

        return redirect()->back();

    }

}
