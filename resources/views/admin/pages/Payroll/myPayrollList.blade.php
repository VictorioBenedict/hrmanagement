@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">My Payroll Record</h6>
    {{-- <div>
        <a href="{{ route('salary.create') }}" class="btn btn-success p-2 text-lg rounded-pill">Create New Salary</a>
    </div> --}}
</div>

<div class="my-5 py-5">
    <!-- Search Form -->
    <div class="d-flex justify-content-end mb-3">
        <div class="input-group rounded w-25 mb-5">
            <form action="{{ route('searchMyPayroll') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." name="search">
                    <button type="submit" class="input-group-text border-0 bg-transparent" id="search-addon" style="display: inline;"> 
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payroll Records Table -->
    <table class="table align-middle mb-3 text-center">
        <thead class="bg-light">
            <tr>
                {{-- <th>Employee</th> --}}
                <th>Date</th>
                {{-- <th>Dept</th>
                <th>Desig</th> --}}
                <th>Month</th>
                <th>Year</th>
                <th>Salary Type</th>
                <th>Salary</th>
                <th>Deduction</th>
                <th>Reason</th>
                <th>Net Pay</th>
                <th>Report</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
            <tr>
                {{-- <td>{{ $payroll->employee->name }}</td> --}}
                <td>{{ $payroll->date }}</td>
                {{-- <td>{{ $payroll->employee->department->department_name }}</td>
                <td>{{ $payroll->employee->designation->designation_name }}</td> --}}
                <td>{{ $payroll->month }}</td>
                <td>{{ $payroll->year }}</td>
                <td>{{ optional($payroll->salaryStructure)->salary_class }}</td>
                <td>{{ optional($payroll->salaryStructure)->total_salary }}</td>
                <td>{{ $payroll->deduction }}</td>
                <td>{{ $payroll->reason }}</td>
                <td>{{ $payroll->total_payable }}</td>
                <td>
                    <a class="btn btn-warning rounded-pill"
                        href="{{ route('mySinglePayroll', ['employeeID' => $payroll->employee_id, 'month' => $payroll->month]) }}">
                        Report
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="w-25 mx-auto">
        {{-- {{ $salaries->links() }} --}}
    </div>
</div>
@endsection
