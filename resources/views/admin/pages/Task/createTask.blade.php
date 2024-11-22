@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Create Task</h6>
</div>

<div class="container my-5 py-5">
    <!-- Section: Form Design Block -->
    <section>
        <div class="text-left w-50 mx-auto mb-5">
            <div class="card mb-3">
                <div class="card-header py-3">
                    <h5 class="text-uppercase">New Task</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('storeTask') }}" method="post">
                        @csrf

                        <!-- Employee & Task Name -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="employeeSelect">Assign Employee</label>
                                    <select name="employee_id" class="form-control" id="employeeSelect" required>
                                        <option value="">Select an Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="task_name">Task Name</label>
                                    <input type="text" id="task_name" name="task_name" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="from_date">From Date</label>
                                    <input type="date" name="from_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="to_date">To Date</label>
                                    <input type="date" name="to_date" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Task Description -->
                        <div class="row mb-3">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label mt-2 fw-bold" for="task_description">Task Description</label>
                                    <textarea id="task_description" name="task_description" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center w-25 mx-auto">
                            <button type="submit" class="btn btn-success p-2 px-3 rounded-pill">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
