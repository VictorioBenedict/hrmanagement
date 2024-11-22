<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserstableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $imagePath = 'https://img.freepik.com/free-psd/3d-rendering-avatar_23-2150833554.jpg?w=740&t=st=1723050239~exp=1723050839~hmac=f36bbe98226056113d3ac1e8459db026bfdfdaec737fab31592d2b669181e135';

        User::create([
            'name' => 'Admin Pogi',
            'role' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at'=>now(),
            'employee_id'=>1,
            'password' => bcrypt('asdasd'),
            'image' => $imagePath,
        ]);

    }
}