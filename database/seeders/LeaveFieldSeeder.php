<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LeaveFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
               // Fetch the column names of the documents table
               $columns = Schema::getColumnListing('leaves');

               // Loop through each column and insert into document_fields
               foreach ($columns as $column) {
                   // Skip columns that you don't want to insert, like 'id', 'created_at', 'updated_at'
                   if (in_array($column, ['id', 'created_at', 'updated_at'])) {
                       continue;
                   }

                   DB::table('leave_fields')->insert([
                       'leave_fieldname' => $column, // Insert the column name as the fieldname
                       'is_visible' => 1, // Default visibility, change as required
                       'created_at' => now(),
                       'updated_at' => now(),
                   ]);
               }

             // Array of document types
               $types = ['Terminal Leave','Vacation Leave', 'Sick Leave'];

               // Insert document types
               $leaveTypes = array_map(fn($type) => [
                   'leave_type_id' => $type,
                   'created_at' => now()
               ], $types);

               DB::table('leave_types')->insert($leaveTypes);

               // Insert document fields
               $leaveFields = array_map(fn($index, $type) => [
                   'leave_fieldname' => $type,
                   'leave_type_id' => $index + 1,
                   'is_visible' => 1,
                   'created_at' => now()
               ], array_keys($types), $types);

               DB::table('leave_fields')->insert($leaveFields);

    }
}
