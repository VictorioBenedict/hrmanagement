<?php

namespace Database\Seeders;

use App\Models\DeletedStatus;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Pending', 'On-Process', 'Ready for Pickup', 'Submitted to D.O'];

        $StatusTypes = array_map(fn($type) => [
            'status_type' => $type,
            'created_at' => now()
        ], $types);

        Status::insert($StatusTypes);

        $StatusDeletedTypes = array_map(function($type, $index) {
            return [
                'status_type' => $type,
                'statuses_id' => $index + 1,  // Increment starting from 1
                'created_at' => now()
            ];
        }, $types, array_keys($types));

       DeletedStatus::insert($StatusDeletedTypes);
    }
}
