@extends('admin.master')

@section('content')
<div class="shadow p-3 d-flex justify-content-between align-items-center">
    <h6 class="text-uppercase">View Salary List</h6>
    <div>
        <a href="{{ route('salary.create.form') }}" class="btn btn-success p-2 text-lg rounded-pill">
            <i class="fa-solid fa-plus me-1"></i>Create New Salary
        </a>
    </div>
</div>

<div class="container my-5 py-5">
    <table class="table table-bordered align-middle mb-3 text-center">
        <thead class="bg-light">
            <tr>
                <th>SL NO</th>
                <th>Salary Class</th>
                <th>Basic Salary</th>
                <th>Medical Expenses</th>
                <th>Mobile Allowance</th>
                <th>House Rent Allowance</th>
                <th>Total Salary</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($salaries as $key => $salary)
            @php
                // Include house rent allowance in total salary calculation
                $totalSalary = $salary->basic_salary + $salary->medical_expenses +
                $salary->mobile_allowance + $salary->houseRent_allowance;
            @endphp
            <tr>
                <td>
                    <p class="fw-bold mb-1">{{ $key + 1 }}</p>
                </td>
                <td>{{ $salary->salary_class }}</td>
                <td>{{ number_format($salary->basic_salary, 2) }} BDT</td>
                <td>{{ number_format($salary->medical_expenses, 2) }} BDT</td>
                <td>{{ number_format($salary->mobile_allowance, 2) }} BDT</td>
                <td>{{ number_format($salary->houseRent_allowance, 2) }} BDT</td>
                <td>{{ number_format($totalSalary, 2) }} BDT</td>
                <td>
                    <a class="btn btn-success rounded-pill" href="{{ route('salaryEdit', $salary->id) }}">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a class="btn btn-danger rounded-pill" href="{{ route('salaryDelete', $salary->id) }}">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="w-25 mx-auto">
        {{ $salaries->links() }}
    </div>
</div>
@endsection
