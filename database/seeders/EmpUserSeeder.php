<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmpUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imagePath = 'https://img.freepik.com/free-psd/3d-rendering-avatar_23-2150833554.jpg?w=740&t=st=1723050239~exp=1723050839~hmac=f36bbe98226056113d3ac1e8459db026bfdfdaec737fab31592d2b669181e135';

        Employee::create([
            'firstname' => 'Super',
            'middlename' => null,
            'lastname' => 'User',
            'name'=>'Admin',
            'role' => 'Admin',
            'email' => 'admin@gmail.com',
            'employee_id'=>'EMP0001',
            'password' => bcrypt('asdasd'),
            'employee_image' => $imagePath,
        ]);
    }
}
