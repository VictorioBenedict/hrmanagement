<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class IncomingFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Fetch the column names of the documents table
         $columns = Schema::getColumnListing('incoming_documents');

         // Loop through each column and insert into document_fields
         foreach ($columns as $column) {
             // Skip columns that you don't want to insert, like 'id', 'created_at', 'updated_at'
             if (in_array($column, ['id', 'created_at', 'updated_at'])) {
                 continue;
             }

             DB::table('incoming_fields')->insert([
                 'incoming_fieldname' => $column, // Insert the column name as the fieldname
                 'is_visible' => 1, // Default visibility, change as required
                 'created_at' => now(),
                 'updated_at' => now(),
             ]);
         }

       // Array of document types
         $types = ['Approval/Signature', 'Endorsement/Transmittal','Submission only'];

         // Insert document types
         $actionTypes = array_map(fn($type) => [
             'action_type_id' => $type,
             'created_at' => now()
         ], $types);

         DB::table('action_types')->insert($actionTypes);

         // Insert document fields
         $incomingFields = array_map(fn($index, $type) => [
             'incoming_fieldname' => $type,
             'action_type_id' => $index + 1,
             'is_visible' => 1,
             'created_at' => now()
         ], array_keys($types), $types);

         DB::table('incoming_fields')->insert($incomingFields);
    }
}
