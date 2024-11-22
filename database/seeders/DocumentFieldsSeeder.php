<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DocumentFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            // Fetch the column names of the documents table
        $columns = Schema::getColumnListing('documents');

        // Loop through each column and insert into document_fields
        foreach ($columns as $column) {
            // Skip columns that you don't want to insert, like 'id', 'created_at', 'updated_at'
            if (in_array($column, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            DB::table('document_fields')->insert([
                'document_fieldname' => $column, // Insert the column name as the fieldname
                'is_visible' => 1, // Default visibility, change as required
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

      // Array of document types
        $types = ['Service Record', 'Orig IPCRF', 'Certificate of Performance Rating', 'Certificate of Employment'];

        // Insert document types
        $documentTypes = array_map(fn($type) => [
            'document_type' => $type,
            'created_at' => now()
        ], $types);

        DB::table('document_types')->insert($documentTypes);

        // Insert document fields
        $documentFields = array_map(fn($index, $type) => [
            'document_fieldname' => $type,
            'document_type_id' => $index + 1,
            'is_visible' => 1,
            'created_at' => now()
        ], array_keys($types), $types);

        DB::table('document_fields')->insert($documentFields);


    }
}
