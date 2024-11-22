<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\CreateUserMail;
use App\Mail\RegisterMail;
use App\Mail\ResetUserPassword;
use App\Mail\UserApproval;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        // Initial validation rules
        $validationRules = [
            'email' => 'required|email',
        ];
    
        // Only add password or new_password validation if necessary
        if (!$request->has('new_password')) {
            $validationRules['password'] = 'required';
        } else {
            $validationRules['new_password'] = 'required|confirmed|regex:/^(?=.*[0-9])(?=.*[^a-zA-Z0-9]).+$/';
        }
    
        // Run the validation
        $val = Validator::make($request->all(), $validationRules);
    
        // Handle validation errors
        if ($val->fails()) {
            if ($request->has('password')) {
                return redirect()->back()->withErrors($val)->withInput();
            } else {
                return redirect()->back()->withErrors($val)->with('warning', 'Set up your new password.')->withInput();
            }
        }
    
        // Find user by email
        $user = User::where('email', $request->email)->first();
    
        // Check if user exists
        if (!$user) {
            return redirect()->back()->with('error', 'Invalid user email or password')->withInput();
        }
    
        // Check if email is verified
        // if (!$user->email_verified_at) {
        //     return redirect()->back()->withErrors(['email' => 'Your email is not verified.'])->withInput();
        // }
    
        // Handle password setting if no new password is provided
        if (!$request->has('new_password')) {
            if ($user && is_null($user->password)) {
                return redirect()->back()->with('warning', 'Set up your new password.')->withInput();
            }
        } else {
            // Update password if new password is provided
            User::where('email', $request->email)->update([
                'password' => bcrypt($request->new_password),
            ]);
    
            // Attempt login with new password
            $credentials = $request->except('_token');
            $credentials['password'] = $request->new_password; // Ensure new password is used
            $login = auth()->attempt($credentials);
    
            // If login is successful
            if ($login) {
                notify()->success('Successfully Logged in');
                return redirect()->route('dashboard');
            }
        }
    
        // Attempt login with old password
        $credentials = $request->except('_token');
        $login = auth()->attempt($credentials);
    
        // If login is successful
        if ($login) {
            notify()->success('Successfully Logged in');
            return redirect()->route('dashboard');
        }
    
        // If login fails
        return redirect()->back()->with('error', 'Invalid user email or password')->withInput();
    }
    

    public function resetPassword($id)
{
    // Find the user by ID
    $user = User::findOrFail($id);
    
    // Perform the password reset logic, e.g., sending an email or resetting password directly
    // Example: Resetting password to a default value (for demonstration purposes)
    $user->update([
        'password'=>null
    ]);

    $body = "Hi {$user->name},<br>Please click the link below to setup your new password. This account will allow you to access the Olongapo City National High School (OCNHS) system, where you can conveniently request official documents and apply for leave.<br>
    <a href='" . route('admin.login') . "' target='_blank'>Set up your password</a>";
    $footer = "<b><i>Olongapo City National High School</i></b>";

    Mail::to($user->email)->send(new ResetUserPassword($body, $footer));

    // Redirect back or show a success message
    return redirect()->back()->with('success', 'Password has been reset successfully!');
}

    

    public function register()
    {
        return view('admin.pages.AdminLogin.adminRegister');
    }

    public function registerPost(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];

        // Run the validation
        $val = Validator::make($request->all(), $validationRules);

        // If validation fails, redirect back with errors
        if ($val->fails()) {
            return redirect()->back()->withErrors($val)->withInput();
        }

        // Check if an admin user already exists
        $configAdmin = User::where('role', 'Admin')->first();

        if ($configAdmin) {
            return redirect()->back()->with('error', 'An admin has already been registered with email: ' . $configAdmin->email);
        }

        // Create a new admin user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Ensure password is hashed
            'role' => 'Admin', // Assign role as Admin
            'employee_id' => 999999999, // For now, using a default employee ID
        ]);

        // Prepare the email body and footer
        $body = "Hi {$user->name},<br>Please click the link below to verify your account. This account will allow you to access the Olongapo City National High School (OCNHS) system.<br>
        <a href='" . route('register.confirm', ['id' => $user->employee_id]) . "' target='_blank'>Verify Account Here</a>";
        
        $footer = "<b><i>Olongapo City National High School</i></b>";

        // Send the verification email
        Mail::to($user->email)->send(new RegisterMail($body, $footer));

        // Redirect to the login page with a success message
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


    public function list(Request $request)
    {
        $currentUser = auth()->user();

        $search = $request->query('search', '');

        $role = $request->query('role', 'Employee');

        $usersQuery = User::query();

        if ($role !== 'All') {
            $usersQuery->where('role', $role);
        }

        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $usersQuery->paginate(8);

        return view('admin.pages.Users.list', compact('users', 'role'));
       
       
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
            'firstname' => 'required',
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
    
        //boolean na pala to
        if($request->hasPassword){
            $defaultPassword=Str::random(10);
        }

        $user = User::create([
            'name' => $request->name,
            'role' => Auth::user()->role == 'System Admin' ? 'Employee' : $request->role,
            'image' => $fileName,
            'email' => $request->email,
            'email_verified_at'=>null,
            'password' => $request->hasPassword ? bcrypt($defaultPassword) : null,
        ]);

        $withPassMessage = $request->hasPassword ? 'login' : 'update' ;
      

        $body = "Hi {$user->name},<br>Welcome to the HR Document Monitoring System! <br> Please click the link below to complete and {$withPassMessage} your account:  <br>
        Email: {$user->email}<br>
        Password: {$defaultPassword}<br>
        <a href='" . route('user.updateLogin',['email'=>$user->email]) . "' target='_blank'>Update your Account Here</a>";
        $footer = "If you have any issues or need assistance, feel free to contact our HR support team.<br>Thank you,<br>HR Team.<br><b><i>Olongapo City National High School</i></b>";

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
        $empData = Employee::where('email', $user->email)->first();
    
        if (!$empData) {
            notify()->warning('User not found.');
            return redirect()->back();
        }
    
        if ($empData->department_id == null || $empData->designation_id == null) {
            notify()->warning('Please complete employee details before approving.');
            return redirect()->route('Employee.edit', ['id' => $empData->id]);
        }
    
        $user->update([
            'email_verified_at' => now()
        ]);
    
        $body = "Hi {$empData->name},<br>Your account has been approved. You can now log in to your account here:<br>
        Click here to log in: <a href='" . route('leave.leaveStatus') . "'>Login</a>";
        
        $footer = "<b><i>Olongapo City National High School</i></b>";
    
        Mail::to($empData->email)->send(new UserApproval($body, $footer));
    
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
                $validationRules['new_password'] = 'required|confirmed|min:6';
            }
            $request->validate($validationRules);

            $role = $user->employee_id === 999999999 ? 'Admin' : (Auth::user()->role == 'Employee' ? 'Employee' : $request->role);

            $updateData = [
                'name' => $request->name,
                'image' => $fileName,
                'email' => $request->email,
                'email_verified_at'=>$request->filled('new_password') ? now() : null,
                'password' => $request->filled('new_password') ? bcrypt($request->new_password) : $user->password,
            ];

            if ($role) {
                $updateData['role'] = $role;
            }

            $user->update($updateData);

           

          $updatedData=Employee::where('id', $user->id)
                ->update([
                    'user_id' => $user->id,
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'employee_image' => $fileName,
                   
                'password' => $request->filled('new_password') ? bcrypt($request->new_password) : $user->password,

                ]);

            notify()->success('User updated successfully.');
            if(auth()->user()->role == 'Employee'){
            return redirect()->route('dashboard');
            }else{
                return redirect()->route('users.list');
            }
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
