<?php

namespace App\Http\Controllers;

use App\Mail\CreateUserMail;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class manageEmployeeController extends Controller
{
    public function addEmployee()
    {
        $departments = Department::all();
        $designations = Designation::all();
        $salaries = SalaryStructure::all();
        return view('admin.pages.manageEmployee.addEmployee', compact('departments', 'designations', 'salaries'));
    }
    public function store(Request $request)
    {
        // dd($request->all());

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'employee_id' => 'required|unique:employees,employee_id',
            'department_id' => 'required',
            'designation_id' => 'required',
            // 'salary_structure_id' => 'required',
            'date_of_birth' => 'required|date',
            // 'hire_date' => 'required|date',
            'email' => 'required|email|max:255|unique:employees,email',
            'phone' => 'required|string|max:20|',
            // 'joining_mode' => 'required',
            'employee_image'=>'required',
            'location' => 'required|string|max:100',
        ]);

        if ($validate->fails()) {
            notify()->error($validate->errors()->first()); // Get the first error message
            return redirect()->back()->withErrors($validate)->withInput();
        }


        $fileName = null;
        if ($request->hasFile('employee_image')) {
            $file = $request->file('employee_image');
            $fileName = date('Ymdhis') . '.' . $file->getClientOriginalExtension();

            $file->storeAs('public',$fileName);
        }

        $isEmailExist = false;

        DB::transaction(function () use ($request, $fileName, &$isEmailExist) { // Pass $isEmailExist by reference
            // Validate if email, name, or employee_id already exists
            $validate = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',  // Check if email is unique in the 'users' table
                'name' => 'required|string|unique:users,name',    // Check if name is unique in the 'users' table
                'employee_id' => 'required|unique:employees,employee_id',  // Check if employee_id is unique in the 'employees' table
            ]);

            // If validation fails, set $isEmailExist to true and exit the transaction
            if ($validate->fails()) {
                $isEmailExist = true;
                return false;  // Stop further execution of the transaction
            }

            // Create the Employee first
            $employee = Employee::create([
                'name' => $request->name,
                'employee_id' => $request->employee_id,
                'employee_image' => $fileName,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'date_of_birth' => $request->date_of_birth,
                'email' => $request->email,
                'phone' => $request->phone,
                'location' => $request->location,
            ]);

            $designation = $employee->designation->designation_name ?? 'User';

            // Create the User associated with the Employee
            $user = User::create([
                'name' => $request->name,
                'employee_id' => $employee->id,  // Associate the user with the employee
                'image' => $fileName,
                'email' => $request->email,
                'role' => ($designation === 'User') ? 'Employee' : 'System Admin'
            ]);

            // Update the Employee with the user_id
            $employee->update(['user_id' => $user->id]);

            $body = "Hi {$user->name},<br>Please click the link below to update your account.<br>
                Email: {$user->email}<br>
                <a href='" . route('user.updateLogin', ['email' => $user->email]) . "' target='_blank'>Update your Account Here</a>";
            $footer = "<b><i>Olongapo City National High School</i></b>";

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
