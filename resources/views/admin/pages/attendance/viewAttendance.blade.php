@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center ">
    <h6 class="text-uppercase">View Attendance List</h6>
</div>
<div class="my-5 py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="input-group rounded w-50">
            <form action="{{ route('searchAttendanceReport') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." name="search">
                    <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon"> 
                    </button>
                </div>
            </form>
        </div>
        <a href="{{ route('attendanceReport') }}" class="btn btn-danger text-capitalize border-0"
            data-mdb-ripple-color="dark">Report</a>
    </div>

    <table class="table align-middle  mb-3 text-center   ">
        <thead class="bg-light">
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Duration</th>
                <th>Date</th>
                <th>month</th>
                <th>Check In</th>
                <th>Late</th>
                <th>Check Out</th>
                <th>Overtime</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $key => $attendance)
            <tr>
                <td>
                    <div>
                        <p class="fw-bold mb-1">{{ $key + 1 }}</p>
                    </div>
                </td>
                <td>{{ $attendance->name }}</td>
                <td>{{ $attendance->department_name }}</td>
                <td>{{ $attendance->designation_name }}</td>
                <td>
                    {{ sprintf('%02d:%02d:%02d', $attendance->duration_minutes / 60, $attendance->duration_minutes % 60,
                    0) }}
                </td>
                <td>{{ $attendance->select_date }}</td>
                <td>{{ $attendance->month }}</td>
                <td>{{ $attendance->check_in }}</td>
                <td>{{ $attendance->late }}</td>
                <td>{{ $attendance->check_out }}</td>
                <td>{{ $attendance->overtime }}</td>
                <td>
                    <a class="btn btn-danger rounded-pill " href="{{ route('attendanceDelete', $attendance->id) }}"><i
                            class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="w-25 mx-auto">
        {{ $attendances->links() }}
    </div>
</div>
@endsection