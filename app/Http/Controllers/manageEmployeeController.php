<?php

namespace App\Http\Controllers;

use App\Mail\CreateUserMail;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class manageEmployeeController extends Controller
{
    // Show the form to add a new employee
    public function addEmployee()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $salaries = SalaryStructure::all();
        
        $lastEmployee = Employee::orderBy('employee_id', 'desc')->first();
        $nextEmployeeId = '001'; 
        
        if ($lastEmployee) {
            $lastEmployeeId = $lastEmployee->employee_id;
            $lastNumber = (int)$lastEmployeeId; 
            $nextNumber = $lastNumber + 1; 
            $nextEmployeeId = str_pad($nextNumber, 3, '0', STR_PAD_LEFT); // Ensure 3 digits (e.g., '001')
        }
        
        while (Employee::where('employee_id', $nextEmployeeId)->exists()) {
            $nextNumber = (int)$nextEmployeeId + 1;
            $nextEmployeeId = str_pad($nextNumber, 3, '0', STR_PAD_LEFT); // Ensure 3 digits
        }
        
    
        // Pass the next available employee_id along with other data
        return view('admin.pages.manageEmployee.addEmployee', compact('departments', 'designations', 'salaries', 'nextEmployeeId'));
    }

    // Archive an employee (set isArchived to true)
   // Archive an employee (set isArchived to true)
public function archiveEmployee($id)
{
    $employee = Employee::find($id);

    if ($employee) {
        $employee->update(['isArchived' => 1]); // Mark as archived
        \Log::info('Employee archived:', ['employee_id' => $id]);
    }

    notify()->success('Employee Archived Successfully.');
    return redirect()->route('manageEmployee.ViewEmployee');  // Redirect to employee list page
}



    // Restore an archived employee (set isArchived to false)
    public function restoreEmployee($id)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->update(['isArchived' => 0]); // Restore employee
        }
        notify()->success('Employee Restored Successfully.');
        return redirect()->route('manageEmployee.ViewEmployee'); // Redirect back to the employee view
    }

    // Fetch and display archived employees
    public function archivedEmployees()
    {
        $archivedEmployees = Employee::where('isArchived', true)->get(); // Fetch all archived employees
        return view('admin.pages.manageEmployee.archivedEmployees', compact('archivedEmployees'));
    }

    // Display active employees (exclude archived ones)
    public function viewEmployees(Request $request)
    {
        $searchQuery = $request->search;

        // If there is a search query, filter employees by name or employee_id, and exclude archived employees
        if ($searchQuery) {
            $employees = Employee::where('firstname', 'like', "%{$searchQuery}%")
                ->orWhere('lastname', 'like', "%{$searchQuery}%")
                ->orWhere('employee_id', 'like', "%{$searchQuery}%")
                ->where('isArchived', false)  // Exclude archived employees
                ->paginate(10);
        } else {
            // Fetch all non-archived employees
            $employees = Employee::where('isArchived', false)->paginate(10);
        }

        return view('admin.pages.manageEmployee.viewEmployees', compact('employees', 'searchQuery'));
    }

    // Store a new employee
    public function store(Request $request)
    {
        // Validate input data
        $validate = Validator::make($request->all(), [
            'firstname' => 'required',
            'employee_id' => 'required|unique:employees,employee_id',
            'department_id' => 'required',
            'designation_id' => 'required',
            'date_of_birth' => 'required|date',
            'email' => 'required|email|max:255|unique:employees,email',
            'phone' => 'required|string|max:20',
            'role' => 'required',
            'employee_image' => 'required',
            'location' => 'required|string|max:100',
        ]);

        if ($validate->fails()) {
            notify()->error($validate->errors()->first()); // Get the first error message
            return redirect()->back()->withErrors($validate)->withInput();
        }

        // Handle file upload for employee image
        $fileName = null;
        if ($request->hasFile('employee_image')) {
            $file = $request->file('employee_image');
            $fileName = date('Ymdhis') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public', $fileName);
        }

        $isEmailExist = false;

        // Database transaction to handle employee and user creation
        DB::transaction(function () use ($request, $fileName, &$isEmailExist) {
            // Validate if email, name, or employee_id already exists
            $validate = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',  // Check if email is unique in the 'users' table
                'employee_id' => 'required|unique:employees,employee_id',  // Check if employee_id is unique in the 'employees' table
            ]);

            // If validation fails, set $isEmailExist to true and exit the transaction
            if ($validate->fails()) {
                $isEmailExist = true;
                return false;  // Stop further execution of the transaction
            }

            // Initialize $defaultPassword
            $defaultPassword = null;

            if ($request->has('hasPassword')) {
                $defaultPassword = Str::random(10);
            }

            $concatName = $request->firstname.' '.$request->middlename.' '.$request->lastname;

            // Create the Employee first
            $employee = Employee::create([
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
                'name'=>$concatName,
                'employee_id' => $request->employee_id,
                'employee_image' => $fileName,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'date_of_birth' => $request->date_of_birth,
                'email' => $request->email,
                'phone' => $request->phone,
                'location' => $request->location,
                'password' => $request->has('hasPassword') ? bcrypt($defaultPassword) : null,
                'role' => $request->role
            ]);

            // Create the User associated with the Employee
            $user = User::create([
                'name' => $concatName,
                'employee_id' => $employee->id,  // Associate the user with the employee
                'image' => $fileName,
                'email' => $request->email,
                'password' => $request->has('hasPassword') ? bcrypt($defaultPassword) : null,
                'role' => Auth::user()->role == 'System Admin' ? 'Employee' : $request->role,
            ]);

            // Update the Employee with the user_id
            $employee->update(['user_id' => $user->id]);

            $withPassMessage = $request->has('hasPassword') ? 'Login' : 'Update';
            $routeLogin = $request->has('hasPassword') ? route('admin.login') : route('user.updateLogin', $user->email);

            // Build the email body
            $body = "Hi {$concatName},<br>Welcome to the HR Document Monitoring System! <br> Please click the link below to complete and {$withPassMessage} your account:  <br>
            Email: {$user->email}<br>
            Password: " . ($defaultPassword ?? 'Not set') . "<br>
            <a href='" . $routeLogin . "' target='_blank'>{$withPassMessage} your Account Here</a>";
            
            $footer = "If you have any issues or need assistance, feel free to contact our HR support team.<br>Thank you,<br>HR Team.<br><b><i>Olongapo City National High School</i></b>";

            // Send the email
            Mail::to($user->email)->send(new CreateUserMail($body, $footer));
        });

        // After the transaction
        if ($isEmailExist) {
            notify()->error('Email, Name, or Employee ID already exists');
            return redirect()->back()->withInput();
        } else {
            notify()->success('New Employee created successfully.');
            return redirect()->route('manageEmployee.ViewEmployee');
        }
    }
}