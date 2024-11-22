<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DocumentFields;
use App\Models\DocumentTypes;
use App\Models\Employee;
use App\Models\User;
use App\Models\Status;
use Illuminate\Http\Request;

class RequestController extends Controller
{

    public function requestDocForm($IsPreview =false)
    {
        $statusPending = Status::where('status_type', 'Pending')->first();

        if (!$statusPending) {
            $statusPending = Status::create([
                'status_type' => 'Pending',
            ]);
        }
        $empData = Employee::all();
        $userAdmin = User::where('role', 'Admin')->get();
        $dep = Department::all();
        $configField = DocumentFields::all();
        $documentType = DocumentTypes::all();
        $documentAdmin = User::where('role', 'System Admin')->get();

        $IsPreview = false; // Example: Replace with your logic for preview status

        return view('admin.pages.Request.requestDocument', compact(
            'empData',
            'userAdmin',
            'dep',
            'IsPreview',
            'configField',
            'documentType',
            'documentAdmin',
            'statusPending'
        ));

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
