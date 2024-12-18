@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">Task List</h6>
</div>

<div class="my-5 py-5">

    <!-- Search Results Message -->
    <div class="fw-normal mb-4">
        <h2 class="fw-normal fs-5 mx-auto text-center rounded-pill p-2 w-50 mb-5 
            @if ($tasks->count() > 0) bg-success
            @else
                bg-danger text-white @endif">
            @if ($tasks->count() === 1)
                Found 1 matching data for "{{ request()->search }}"
            @elseif ($tasks->count() > 1)
                Found {{ $tasks->count() }} matching data for "{{ request()->search }}"
            @else
                No Data found for "{{ request()->search }}"
            @endif
        </h2>
    </div>

    <!-- Department Table start -->
    @if ($tasks->count() > 0)
    <div class="w-100 mx-auto">
        <div>
            <table class="table align-middle mb-3 text-center">
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
    @endif
</div>

@endsection
