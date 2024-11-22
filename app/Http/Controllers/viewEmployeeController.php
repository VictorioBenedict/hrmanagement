<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\SalaryStructure;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class viewEmployeeController extends Controller
{
    // public function viewEmployee()
    // {
    //     $employees = Employee::with(['department', 'designation', 'salaryStructure'])->paginate(5);
    //     return view('admin.pages.manageEmployee.viewEmployee', compact('employees'));
    // }
    public function viewEmployee()
    {
        
        $departments = Department::all();
        $employees = Employee::with(['department', 'designation', 'salaryStructure'])
                             ->latest('id')
                             ->paginate(10);
    
    
        // Pass departments, employees, and searchQuery to the view
        return view('admin.pages.manageEmployee.viewEmployee', compact('employees', 'departments'));
    }


    // Delete employee
    public function delete($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $user = $employee->user; 
            if ($user) {
                $user->delete();
            }
            $employee->delete();
    
            notify()->success('Your information has been deleted successfully.');
            return redirect()->route('manageEmployee.ViewEmployee')->with('success', 'Employee and user deleted successfully.');
        } else {
            return redirect()->route('manageEmployee.ViewEmployee')->with('error', 'Employee not found.');
        }
    }
    


    // Edit Employee
    public function edit($id)
    {
        $employee = Employee::find($id);
        $departments = Department::all();
        $designations = Designation::all();
        $salaries = SalaryStructure::all();
        return view('admin.pages.manageEmployee.EditEmployee', compact('employee', 'departments', 'designations', 'salaries'));
    }

    // Update Employee
    public function update(Request $request, $id)
{
    // Find employee by ID
    $employee = Employee::find($id);
    if ($employee) {
        // Validate input data
        $validate = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'employee_id' => 'required',
            'department_id' => 'required',
            'designation_id' => 'required',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
            'location' => 'required|string|max:100',
        ]);

        // If validation fails, return with error messages
        if ($validate->fails()) {
            notify()->error($validate->getMessageBag());
            return redirect()->back();
        }

        // Handle file upload for employee image
        $fileName = $employee->employee_image;
        if ($request->hasFile('employee_image')) {
            $file = $request->file('employee_image');
            
            // Validate the file type (optional, for security reasons)
            $validatedImage = $request->validate([
                'employee_image' => 'mimes:jpeg,png,jpg|max:2048', // Max size: 2MB
            ]);

            // Generate a unique filename
            $fileName = date('Ymdhis') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $fileName); // Store the file in 'public' disk
        }

        // Concatenate the full name of the employee
        $concatName = $request->firstname . ' ' . $request->middlename . ' ' . $request->lastname;

        // Update employee data
        $employee->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'name' => $concatName,
            'employee_id' => $request->employee_id,
            'employee_image' => $fileName,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'location' => $request->location,
            'role' => $request->role,
        ]);

        // Update the user associated with this employee
        $user = User::where('employee_id', $employee->id)->first();

        if ($user) {
            // If the logged-in user is an admin, preserve their admin role
            if (Auth::user()->role == 'Admin') {
                // Admins can update their profile without changing their role
                $userUpdate = [
                    'name' => $concatName,
                    
                    'role' => $user->role, // Preserve current role (Admin, HR, etc.)
                ];
            } else {
                // If the user is an employee, make sure the role stays 'Employee'
                $userUpdate = [
                    'name' => $concatName,
                 
                    'role' => 'Employee',  // Fix role to 'Employee' for this user
                ];
            }

            // If an image was uploaded, update the user's image
            if ($fileName) {
                $userUpdate['image'] = $fileName;
            }

            // Update the user record
            $user->update($userUpdate);
        } else {
            // If no user is found associated with this employee, log the error or notify the user
            notify()->error('Associated user not found for this employee.');
            return redirect()->back();
        }

        // Notify success
        notify()->success('Your information has been updated successfully.');

        if (Auth::user()->role == 'Employee') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('manageEmployee.ViewEmployee');
        }

    } else {
        notify()->error('Employee not found.');
        return redirect()->back();
    }
}


    

    public function profile($id)
    {
        $employee = Employee::find($id);
        $departments = Department::all();
        $designations = Designation::all();
        $salaries = SalaryStructure::all();
        return view('admin.pages.manageEmployee.employeeProfile', compact('employee', 'departments', 'designations', 'salaries'));
    }

    // search Employee

    public function search(Request $request)
    {
        $searchTerm = $request->search;

        // Query builder for Employee model
        $query = Employee::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('employee_id', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('department', function ($departmentQuery) use ($searchTerm) {
                        $departmentQuery->where('department_name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('designation', function ($designationQuery) use ($searchTerm) {
                        $designationQuery->where('designation_name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->orWhere('joining_mode', 'LIKE', '%' . $searchTerm . '%');
                // Add more conditions based on your search requirements
            });
        }

        $employees = $query->paginate(10); // Change 10 to the desired number of items per page

        $departments = Department::all();
        $designations = Designation::all();
        // $salaries = SalaryStructure::all();

        $searchQuery = $searchTerm;

        return view("admin.pages.manageEmployee.viewEmployee", compact('employees', 'departments', 'designations','searchQuery'));
    }
}