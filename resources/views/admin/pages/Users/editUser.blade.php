@extends('admin.master')

@section('content')

<section>
    <div class="shadow p-3 d-flex justify-content-between align-items-center mb-2">
        <h6 class="text-uppercase" style="color:black;">Change Password Account</h6>
    </div>
    <section>
        <div class="mx-auto">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="mb-0 text-uppercase">User Account Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('update', $user->id) }}" method="post" enctype="multipart/form-data">

                        @csrf
                        @method('put')

                        <!-- Display session messages -->
                        @if (session()->has('myError'))
                            <p class="alert alert-danger">{{ session()->get('myError') }}</p>
                        @endif

                        @if (session()->has('message'))
                            <p class="alert alert-success">{{ session()->get('message') }}</p>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Enter User Name:</label>
                                    <input value="{{ $user->name }}" required placeholder="Employee Name" type="text" id="form11Example1" name="name" class="form-control" />
                                </div>
                                <div class="mt-2">
                                    <br>
                                    @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Select Role:</label>
                                    <select {{ $user->employee_id === 999999999 || Auth::user()->role == 'Employee' || Auth::user()->role == 'System Admin' ? 'disabled' : '' }} id="form11Example1" name="role" class="form-control">
                                        <option value="{{ $user->role }}" disabled selected>Current: {{ $user->role }}</option>
                                        <option value="Employee">Employee(User)</option>
                                        <option value="System Admin">System Admin(HR)</option>
                                    </select>
                                </div>
                                <div class="mt-2">
                                    <br>
                                    @error('role')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                    @if ($user->employee_id === 999999999)
                                    <div class="alert alert-warning">The Config Admin can only be set up once</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Enter User Email</label>
                                    <input value="{{ $user->email }}" required placeholder="Enter Email" type="email" id="form11Example1" name="email" class="form-control" />
                                </div>
                                <div class="mt-2">
                                    <br>
                                    @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="form11Example1">Image</label>
                                    <input type="file" id="form11Example1" name="user_image" class="form-control" accept="image/jpeg, image/png" />
                                </div>
                                <div class="mt-2">
                                    <br>
                                    @error('image')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->role == 'Admin')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="old_password">Confirm Old Password:</label>
                                    <div class="input-group gap-2">
                                        <input type="password" id="old_password" name="old_password" placeholder="Confirm Old Password" class="form-control col-6" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('old_password')">
                                                <i class="fas fa-eye" id="old_password_eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                @error('old_password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="new_password">New Password:</label>
                                    <div class="input-group gap-2">
                                        <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" class="form-control col-6" />
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('new_password')">
                                                <i class="fas fa-eye" id="new_password_eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                @error('new_password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="confirm_new_password">Confirm New Password:</label>
                                    <div class="input-group gap-2">
                                        <input type="password" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm New Password" class="form-control col-6"/>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('confirm_new_password')">
                                                <i class="fas fa-eye" id="confirm_new_password_eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                @error('confirm_new_password')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Modal -->


<script>
    // Password toggle function
    function togglePassword(id) {
        var input = document.getElementById(id);
        var eyeIcon = document.getElementById(id + '_eye');
        if (input.type === "password") {
            input.type = "text";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

@endsection
