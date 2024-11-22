<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;  // Add this for random string generation

class DesignationController extends Controller
{
    public function designation()
    {   
        $designations = Designation::paginate(10);  // Paginate if needed
        $departments = Department::all();
        $salaries = SalaryStructure::all();
        return view('admin.pages.Organization.Designation.designation', compact('departments', 'salaries', 'designations'));
    }

    public function designationStore(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'designation_name' => 'required|unique:designations,designation_name',  // Add uniqueness check here
        ], [
            'designation_name.required' => 'The Position Name is required.',
            'designation_name.unique' => 'The Position Name is already taken.',
        ]);
    
        // If validation fails, show error message and return back with errors
        if ($validate->fails()) {
            // Notify with the first error message
            notify()->error($validate->errors()->first()); 
            // Redirect back with validation errors and input
            return redirect()->back()->withErrors($validate)->withInput();
        }
    
        // Generate a random designation ID
        $designation_id = Str::random(10); 
    
        // Create a new designation
        Designation::create([
            'designation_id' => $designation_id,  
            'designation_name' => $request->designation_name,
        ]);
    
        // Success notification
        notify()->success('New Position created successfully');
        return redirect()->back();
    }

    public function designationList()
    {
        $designations = Designation::with(['department'])
            ->latest('id')
            ->paginate(5);
    
        $searchQuery = null;
    
        return view('admin.pages.Organization.Designation.designationList', compact('designations', 'searchQuery'));
    }
    
    

    public function delete($id)
    {
        $department = Designation::find($id);
        if ($department) {
            $department->delete();
        }
        notify()->success('Position Deleted Successfully');
        return redirect()->back();
    }

    public function edit($id)
    {
        $designation = Designation::find($id);
        $departments = Department::all(); // You might not need all departments if you're not using them in the form
        $salaries = SalaryStructure::all(); // Same as above
    
        // If you're only showing the designation for editing, you might not need these if they aren't part of the form
        return view('admin.pages.Organization.Designation.editDesignation', compact('designation', 'departments', 'salaries'));
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'designation_name' => 'required|string|max:255',
        ]);
    
        $designation = Designation::findOrFail($id);
        $designation->update([
            'designation_name' => $request->designation_name,
        ]);
        notify()->success('Position Updated Successfully');
        return redirect()->back();
    }
    
    
}
