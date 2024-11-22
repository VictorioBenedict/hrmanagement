<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserstableSeeder::class);
        $this->call(DocumentFieldsSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(LeaveFieldSeeder::class);
        $this->call(EmpUserSeeder::class);
        $this->call(IncomingFieldSeeder::class);

   }

}
