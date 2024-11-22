@extends('admin.master')

@section('content')

<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Task List</h6>
</div>

<div class="my-5 py-5">

    <!-- Search Form -->
    <div class="d-flex justify-content-end mb-4">
        <div class="input-group rounded w-25">
            <form action="{{ route('searchTask') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." name="search" />
                    <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon">
                        <i class="fa fa-search"></i> <!-- Added search icon for better UX -->
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Department Table Start -->
    <div class="w-100 mx-auto">
        <div class="table-responsive">
            <table class="table table-striped align-middle text-center mb-3">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Task Name</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Total Days</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $key => $task)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $task->employee->name }}</td>
                        <td>{{ $task->employee->department->department_name }}</td>
                        <td>{{ $task->employee->designation->designation_name }}</td>
                        <td>{{ $task->task_name }}</td>
                        <td>{{ $task->from_date }}</td>
                        <td>{{ $task->to_date }}</td>
                        <td>{{ $task->total_days }}</td>
                        <td>
                            @if($task->status == 'completed on time')
                                <span class="text-white fw-bold bg-success rounded-pill p-2">Completed on time</span>
                            @elseif($task->status == 'completed in late')
                                <span class="text-white fw-bold bg-danger rounded-pill p-2">Completed Late</span>
                            @else
                                <span class="text-white fw-bold bg-warning rounded-pill p-2">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
