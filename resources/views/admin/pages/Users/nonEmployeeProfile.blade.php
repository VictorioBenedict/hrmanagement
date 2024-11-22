@extends('admin.master')

@section('content')

<!-- Profile Title Section -->
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">My Profile</h6>
</div>

<!-- Profile Content Section -->
<section>
    <br>
    <div class="container" style="width: 100%; background-color: #f8f9fa; padding-top: 30px;">
        <div class="row">
            <!-- Left Section: User Profile Image -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <!-- Profile Image -->
                        <img src="{{ url('/uploads//' . $user->user_image) }}" alt="User Image"
                             class="rounded-circle mx-auto img-fluid"
                             style="width: 150px; height: 150px; object-fit: cover;">
                        <h5 class="my-3">{{ $user->name }}</h5>
                    </div>
                </div>
            </div>

            <!-- Right Section: User Details -->
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <!-- Full Name -->
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->name }}</p>
                            </div>
                        </div>
                        <hr>

                        <!-- Role -->
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Role</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->role }}</p>
                            </div>
                        </div>
                        <hr>

                        <!-- Email -->
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
