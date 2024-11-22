<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class OrganizationController extends Controller
{
    public function department()
    {
        $department = Department::orderBy('created_at', 'desc')->get();

        $departments = Department::where('isArchived',null)->paginate(5);
        $searchQuery = [];
        return view('admin.pages.Organization.Department.department', compact('departments','searchQuery','department'));
    }

    public function departmentArchive()
    {
        $departments = Department::where('isArchived',1)->paginate(5);
        $searchQuery = [];
        return view('admin.pages.Organization.Department.department', compact('departments','searchQuery'));
    }
    // public function departmentList()
    // {
    //     $departments = Department::all();
    //     return view('admin.pages.Organization.Department.department', compact('departments'));
    // }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'department_name' => 'required|unique:departments,department_name',
        ], [
            'department_name.required' => 'The Department Name is required.',
            'department_name.unique' => 'The Department Name is already taken.',
        ]);
    
        // If validation fails
        if ($validate->fails()) {
            // Notify with the first error message
            notify()->error($validate->errors()->first()); 
            // Redirect back with all errors
            return redirect()->back()->withErrors($validate)->withInput();
        }
    
        // Create the new department
        Department::create([
            'department_name' => $request->department_name,
            'department_id' => Str::random(6), // or any logic for creating department_id
        ]);
    
        // Success notification
        notify()->success('New Department created successfully.');
        return redirect()->back();
    }

    public function archiveDepartment($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->update(['isArchived'=>1]);
        }
        notify()->success('Department Archived Successfully.');
        return redirect()->back();
    }

    public function restoreDepartment($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->update(['isArchived'=>null]);
        }
        notify()->success('Department Restore Successfully.');
        return redirect()->back();
    }

    public function delete($id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->delete();
        }
        notify()->success('Department Deleted Successfully.');
        return redirect()->back();
    }
    public function edit($id)
    {
        $department = Department::find($id);
        return view('admin.pages.Organization.Department.editDepartment', compact('department'));
    }
    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        if ($department) {
            $department->update([
                'department_name' => $request->department_name,
                // 'department_id' => $request->department_id,
            ]);
            notify()->success('Updated successfully.');
            return redirect()->route('organization.department');
        }
    }


    public function searchDepartment(Request $request,$mode)
    {
        $searchTerm = $request->search;

        $departments = Department::where(function ($query) use ($searchTerm) {
            $query->where('department_name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('department_id', 'LIKE', '%' . $searchTerm . '%')
                ->where('isArchived','=',$mode = 'archive' ? 1 : null);
        })->paginate(5);

      
        // dd($departments);

        $searchQuery = $searchTerm;

        return view('admin.pages.Organization.Department.department', compact('departments','searchQuery'));
    }

    // public function searchArchiveDepartment(Request $request)
    // {
    //     $searchTerm = $request->search;
        
    //     $departments = Department::where(function ($query) use ($searchTerm) {
    //         $query->where('department_name', 'LIKE', '%' . $searchTerm . '%')
    //             ->where('isArchived',1);
    //     })->paginate(5);

    //     $searchQuery = $searchTerm;

    //     return view('admin.pages.Organization.Department.department', compact('departments','searchQuery'));
    // }
}
