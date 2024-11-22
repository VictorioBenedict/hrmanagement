<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Department::insert([
    [
        'id' => 1,
        'department_name' => 'Office Management',
        'department_id' => 'OFC',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 2,
        'department_name' => 'Science Department',
        'department_id' => 'SCI',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 3,
        'department_name' => 'Mathematics Department',
        'department_id' => 'MATH',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 4,
        'department_name' => 'English Department',
        'department_id' => 'ENG',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 5,
        'department_name' => 'Filipino Department',
        'department_id' => 'FIL',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 6,
        'department_name' => 'Social Studies Department',
        'department_id' => 'SS',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 7,
        'department_name' => 'Physical Education Department',
        'department_id' => 'PE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 8,
        'department_name' => 'Music Department',
        'department_id' => 'MUS',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 9,
        'department_name' => 'Art Department',
        'department_id' => 'ART',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 10,
        'department_name' => 'Home Economics Department',
        'department_id' => 'HE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 11,
        'department_name' => 'Technology and Livelihood Education (TLE) Department',
        'department_id' => 'TLE',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 12,
        'department_name' => 'Guidance and Counseling Department',
        'department_id' => 'GNC',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 13,
        'department_name' => 'Special Education Department',
        'department_id' => 'SPED',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 14,
        'department_name' => 'Library and Media Services Department',
        'department_id' => 'LIB',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 15,
        'department_name' => 'Health and Nutrition Department',
        'department_id' => 'HN',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 17,
        'department_name' => 'ICT and Computer Studies Department',
        'department_id' => 'ICT',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 24,
        'department_name' => 'Security and Safety Department',
        'department_id' => 'SEC',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 25,
        'department_name' => 'Facilities and Maintenance Department',
        'department_id' => 'FAC',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 30,
        'department_name' => 'Human Resources Department',
        'department_id' => 'HR',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 44,
        'department_name' => 'Cafeteria Services Department',
        'department_id' => 'CS',
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id' => 45,
        'department_name' => 'Maintenance and Housekeeping Department',
        'department_id' => 'M&H',
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);

    }
}
