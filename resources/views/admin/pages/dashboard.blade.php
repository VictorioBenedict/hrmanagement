@extends('admin.master')

@section('content')
<div style="background-color: #DF7A30; text-align: center;">
    <p class="fw-medium fs-5 animated-text">
        <span style="font-weight: bold;">Hello,</span><br>
        <span class="fw-bold" style="font-weight: bold;">{{ auth()->user()->name }}</span>
        <span style="font-weight: bold;">Welcome</span>
        <span style="font-weight: bold;">to</span>
        <span style="font-weight: bold;">Document Monitoring System</span>
        <hr>
    </p>
</div>

<br><br>

<section class="mb-3 mb-lg-5">
    <div class="row mb-3">
        
        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'System Admin')
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-warning">{{ $pendingLeaves }}</h4>
                            <p class="subtitle text-sm text-muted mb-0">Leave Request</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/leave.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('leave.leaveStatus') }}" style="background-color: #5A6D7B;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">View Details</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if (Auth::user()->role == 'Admin' || Auth::user()->role == 'System Admin')
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-info">{{ $documents }}</h4>
                            <p class="subtitle text-sm text-muted mb-0">Document Request</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/task.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('document.documentStatus') }}" style="background-color: #3D5A5C;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">View Details</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if (Auth::user()->role == 'Admin')
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-red">{{ $employees }}</h4>
                            <p class="subtitle text-sm text-muted mb-0">Total Employee</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/teamwork.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('manageEmployee.ViewEmployee') }}" style="background-color: #6B7A4E;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">View Details</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if (Auth::user()->role == 'Admin')
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-blue">{{ $departments }}</h4>
                            <p class="subtitle text-sm text-muted mb-0">Department</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/department.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('organization.department') }}" style="background-color: #4B5D4B;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">View Details</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if (Auth::user()->role == 'Admin')
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-info">{{ $users }}</h4>
                            <p class="subtitle text-sm text-muted mb-0">Users</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/users.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('users.list') }}" style="background-color: #3B3B3B;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">View Details</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif
      

        @if (Auth::user()->role == 'Employee')
        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-info">Request for Leave</h4>
                            <p class="subtitle text-sm text-muted mb-0">Leave Request</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/task.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('leave.documentForm', 0) }}" style="background-color: #5A6D7B;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-white">
                            <div class="col-10">
                                <p class="mb-0">Request Now</p>
                            </div>
                            <div class="col-2 text-end"><i class="fas fa-caret-up"></i></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-info">Request Form For Receiving Document</h4>
                            <p class="subtitle text-sm text-muted mb-0">Document Request</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/task.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('request.documentForm', 0) }}" style="background-color: #3D5A5C;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">Request Now</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <div class="col-sm-6 col-lg-3 mb-3">
            <div class="card h-100 w-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="fw-normal text-info">Submit a Submission Notice</h4>
                            <p class="subtitle text-sm text-muted mb-0">Document Submission</p>
                        </div>
                        <div class="flex-shrink-0 ms-3">
                            <img class="img-fluid custom-small-img" src="{{ asset('assests/image/task.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <a class="text-decoration-none" href="{{ route('incoming.documentForm', 0) }}" style="background-color: #3D5A5C;">
                    <div class="card-footer py-3">
                        <div class="row align-items-center text-light">
                            <div class="col-10">
                                <p class="mb-0">Request Now</p>
                            </div>
                            <div class="col-2 text-end">
                                <i class="fas fa-caret-up"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
