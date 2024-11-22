<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\CreateUserMail;
use App\Mail\RegisterMail;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login()
    {
        $hasAdmin = User::where('role', 'Admin')->exists();

        return view('admin.pages.AdminLogin.adminLogin', compact('hasAdmin'));
    }

    public function loginPost(Request $request)
    {
        $validationRules = [
            'email' => 'required|email',
        ];

        if (!$request->new_password) {
            $validationRules['password'] = 'required';
        } else {
            $validationRules['new_password'] = 'required|confirmed|regex:/^(?=.*[0-9])(?=.*[^a-zA-Z0-9]).+$/';
        }


        $val = Validator::make($request->all(), $validationRules);

        if ($val->fails()) {
            if($request->password){
                return redirect()->back()->withErrors($val)->withInput();
            }else{
                return redirect()->back()->withErrors($val)->with('warning', 'Set up your new password.')->withInput();
            }
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error','invalid user email or password')->withInput();
        }

       if(!$user->email_verified_at){
            return redirect()->back()->withErrors(['email' => 'Your email is not verified.'])->withInput();
        }

        if(!$request->new_password){
            if ($user && is_null($user->password)) {
                return redirect()->back()->with('warning', 'Set up your new password.')->withInput();
            }
        }else{
            User::where('email',$request->email)->update([
                'password'=>bcrypt($request->new_password),
            ]);
            return redirect()->back()->with('success','Success please login')->withInput();
        }

        $credentials = $request->except('_token');

        $login = auth()->attempt($credentials);
        if ($login) {
            notify()->success('Successfully Logged in');
            return redirect()->route('dashboard');
        }

        return redirect()->back()->with('error','invalid user email or password')->withInput();
    }

    public function register()
    {
        return view('admin.pages.AdminLogin.adminRegister');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4|confirmed',
        ]);

        $configAdmin = User::first();

        if ($configAdmin) {
            return redirect()->back()->with('error', 'Config admin already registered with email ' . $configAdmin->email);
        }

        $user = User::create([
            'name' => $request->username,
            'employee_id'=>999999999,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'Admin'
        ]);

        $body = "Hi {$user->name},<br>Please click the link below to verify your account.<br>
        <a href='" . route('register.confirm',['id'=>$user->employee_id]) . "' target='_blank'>Verify Account Here</a>";
        $footer = "<b><i>Olongapo City National High School</i></b>";

        Mail::to($user->email)->send(new RegisterMail($body, $footer));

        return redirect()->route('admin.login')->with('warning', 'Success! Check your email to complete the registration.');
    }

    public function registerVerify($employeeId){
      User::where('employee_id',$employeeId)->update(['email_verified_at'=>now()]);
      return redirect()->route('admin.login')->with('success', 'Success! Registered successfully please login');
    }


    public function logout()
    {

        auth()->logout();
        notify()->success('Successfully Logged Out');
        return redirect()->route('admin.login');
    }


    public function list()
    {
        $currentUser = auth()->user();

        // Get users based on the current user's role
        $users = User::when($currentUser->role !== 'Admin', function ($query) use ($currentUser) {
            // Remove 'Admin' role if the current user is not an admin
            $query->where('role', '!=', 'Admin');

            // If the current user is an 'Employee', only show their own record
            if ($currentUser->role === 'Employee') {
                $query->where('id', $currentUser->id);
            }
        })->paginate(5);

        $employee = Employee::first(); // Fetches the first employee

        $searchQuery = [];

        $employees = User::where('role','Employee')->all();
        $systemadmin = User::where('role','System Admin')->all();

        return view('admin.pages.Users.list', compact('users', 'employee','searchQuery','systemadmin','employees'));
    }


    public function createForm()
    {
        // $employee = Employee::find($employeeId);

        // if (!$employee) {
        //     return redirect()->back()->withErrors('Employee not found');
        // }

        return view('admin.pages.Users.createForm');
    }


    public function userProfile($id)
    {
        $user = User::with('employee')->find($id);
        $employee = $user->employee ?? null;
        $departments = Department::all();
        $designations = Designation::all();
        $salaries = SalaryStructure::all();
        return view('admin.pages.Users.userProfile', compact('user', 'employee', 'departments', 'designations', 'salaries'));
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'user_image'=>'required'
        ]);

        if ($validate->fails()) {
            $errorMessages = implode(' ', $validate->errors()->all());
            notify()->error('Invalid Credentials. ' . $errorMessages);
            return redirect()->back()->withInput();
        }

        $fileName = null;
        if ($request->hasFile('user_image')) {
            $file = $request->file('user_image');
            $fileName = date('Ymdhis') . '.' . $file->getClientOriginalExtension();

            $file->storeAs('public', $fileName);
        }

        $user = User::create([
            'name' => $request->name,
            'role' => Auth::user()->role == 'System Admin' ? 'Employee' : $request->role,
            'image' => $fileName,
            'email' => $request->email,
            'email_verified_at'=>null,
            'password' => $request->password ? bcrypt($request->password) : null,
        ]);

        $body = "Hi {$user->name},<br>Please click the link below to update your account.<br>
        Email: {$user->email}<br>
        <a href='" . route('user.updateLogin',['email'=>$user->email]) . "' target='_blank'>Update your Account Here</a>";
        $footer = "<b><i>Olongapo City National High School</i></b>";

        Mail::to($user->email)->send(new CreateUserMail($body, $footer));

        // Find associated employee using the email and assign user_id to employee
        $employee = Employee::where('email', $request->email)->first();
        if ($employee) {
            $employee->user_id = $user->id;
            $employee->save();
        }else{
            $lastEmpId = Employee::max('employee_id');
            $newEmpId = $lastEmpId ? $lastEmpId + 1 : 1;

            $employee = Employee::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'user_id' => $user->id,
                'employee_id'=> $newEmpId, //default but this is can be change
                'employee_image'=>$request->hasFile('user_image') ? $fileName : null
            ]);

            $user->update([
                'employee_id'=>$employee->id
            ]);
        }

        notify()->success('User created successfully.');
        return redirect()->route('users.list');
    }

    public function updatePassword($email){
        return redirect()->route('admin.login')->with('warning', 'Set up your new password.')->withInput(['email'=>$email]);
    }

    // single  profile

    public function myProfile()
    {
        $user = Auth::user();
        if ($user->employee) {
            $employee = $user->employee;
            $departments = Department::all();
            $designations = Designation::all();
            $salaries = SalaryStructure::all();
            return view('admin.pages.Users.employeeProfile', compact('user', 'employee', 'departments', 'designations', 'salaries'));
        } else {
            return view('admin.pages.Users.nonEmployeeProfile', compact('user'));
        }
    }

    // user delete

    public function userDelete($id)
    {
        $user = User::find($id);
        $employee = Employee::where('user_id',$id)->first();
        if ($user) {
            $user->delete();
        }

        if ($employee) {
            $employee->delete();
        }

        notify()->success('User Deleted Successfully.');
        return redirect()->back();
    }

    public function userApprove($id)
    {
        $user = User::find($id);

        $empData = Employee::where('email',$user->email)->first();

        if ($empData->department_id == null || $empData->designation_id == null) {
            notify()->warning('Please complete employee details before approving');
            return redirect()->route('Employee.edit', ['id' => $empData->id]);
        }


        if ($user) {
            $user->update([
                'email_verified_at'=>now()
            ]);
        }

        notify()->success('User Approved Successfully.');
        return redirect()->back();
    }

    // User edit, update

    public function userEdit($id)
    {
        $user = User::find($id);
        $employee = Employee::find($id);
        return view('admin.pages.Users.editUser', compact('user', 'employee'));
    }


    public function userUpdate(Request $request, $id)
    {
        $user = User::find($id);

        if ($user) {
            $fileName = $user->image;
            if ($request->hasFile('user_image')) {
                $file = $request->file('user_image');
                $fileName = now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public', $fileName);
            }

            $validationRules = ['name' => 'min:4'];
            if ($request->filled('old_password')) {
                $validationRules['old_password'] = ['required', new MatchOldPassword];
                $validationRules['new_password'] = 'confirmed|min:6';
            }
            $request->validate($validationRules);

            $role = $user->employee_id === 999999999 ? 'Admin' : (Auth::user()->role == 'Employee' ? 'Employee' : $request->role);

            $updateData = [
                'name' => $request->name,
                'image' => $fileName,
                'email' => $request->email,
                'password' => $request->filled('new_password') ? bcrypt($request->new_password) : $user->password,
            ];

            if ($role) {
                $updateData['role'] = $role;
            }

            $user->update($updateData);



            Employee::where('email', $request->email)
                ->update([
                    'user_id' => $user->id,
                    'name'=>$user->name,
                    'email'=>$user->email,
                    'employee_image' => $fileName
                ]);

            notify()->success('User updated successfully.');
            return redirect()->route('users.list');
        }
    }

    // Search User
    public function searchUser(Request $request)
    {
        $searchTerm = $request->search;
        if ($searchTerm) {
            $users = User::where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('role', 'LIKE', '%' . $searchTerm . '%')
                ->paginate(10);
        } else {
            $users = User::paginate(10);
        }


        $searchQuery = $searchTerm;

        return view('admin.pages.Users.list', compact('users','searchQuery'));
    }
}
