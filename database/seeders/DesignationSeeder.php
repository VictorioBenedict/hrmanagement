<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $designations = [
            [
                'id' => 4,
                'designation_name' => 'Admin Aide I',
                'designation_id' => 'AdminAide1',
            ],
                [
                    'id' => 5,
                    'designation_name' => 'Admin Aide II',
                    'designation_id' => 'AdminAide2',
                ],
                [
                    'id' => 6,
                    'designation_name' => 'Admin Aide III',
                    'designation_id' => 'AdminAide3',
                ],
                [
                    'id' => 7,
                    'designation_name' => 'Admin Aide IV',
                    'designation_id' => 'AdminAide4',
                ],
                [
                    'id' => 8,
                    'designation_name' => 'Admin Aide V',
                    'designation_id' => 'AdminAide5',
                ],
                // Teacher Positions
                [
                    'id' => 9,
                    'designation_name' => 'Teacher I',
                    'designation_id' => 'Teacher1',
                ],
                [
                    'id' => 10,
                    'designation_name' => 'Teacher II',
                    'designation_id' => 'Teacher2',
                ],
                [
                    'id' => 11,
                    'designation_name' => 'Teacher III',
                    'designation_id' => 'Teacher3',
                ],
                [
                    'id' => 12,
                    'designation_name' => 'Master Teacher I',
                    'designation_id' => 'MasterTeacher1',
                ],
                [
                    'id' => 13,
                    'designation_name' => 'Master Teacher II',
                    'designation_id' => 'MasterTeacher2',
                ],
                [
                    'id' => 14,
                    'designation_name' => 'Master Teacher III',
                    'designation_id' => 'MasterTeacher3',
                ],
                [
                    'id' => 15,
                    'designation_name' => 'Head Teacher I',
                    'designation_id' => 'HeadTeacher1',
                ],
                [
                    'id' => 16,
                    'designation_name' => 'Head Teacher II',
                    'designation_id' => 'HeadTeacher2',
                ],
                [
                    'id' => 17,
                    'designation_name' => 'Head Teacher III',
                    'designation_id' => 'HeadTeacher3',
                ],
                [
                    'id' => 18,
                    'designation_name' => 'Principal I',
                    'designation_id' => 'Principal1',
                ],
                [
                    'id' => 19,
                    'designation_name' => 'Principal II',
                    'designation_id' => 'Principal2',
                ],
                [
                    'id' => 20,
                    'designation_name' => 'Principal III',
                    'designation_id' => 'Principal3',
                ],
                [
                    'id' => 21,
                    'designation_name' => 'District Supervisor',
                    'designation_id' => 'DistrictSupervisor',
                ],
                [
                    'id' => 22,
                    'designation_name' => 'Assistant School Principal',
                    'designation_id' => 'AssistantPrincipal',
                ],
                [
                    'id' => 23,
                    'designation_name' => 'School Principal',
                    'designation_id' => 'SchoolPrincipal',
                ],
                [
                    'id' => 24,
                    'designation_name' => 'Education Program Specialist I',
                    'designation_id' => 'EPS1',
                ],
                [
                    'id' => 25,
                    'designation_name' => 'Education Program Specialist II',
                    'designation_id' => 'EPS2',
                ],
                [
                    'id' => 26,
                    'designation_name' => 'Education Program Specialist III',
                    'designation_id' => 'EPS3',
                ],
                [
                    'id' => 27,
                    'designation_name' => 'Curriculum Development Specialist',
                    'designation_id' => 'CurriculumDevSpecialist',
                ],
                [
                    'id' => 28,
                    'designation_name' => 'Teacher Aide',
                    'designation_id' => 'TeacherAide',
                ],
                [
                    'id' => 29,
                    'designation_name' => 'School Nurse',
                    'designation_id' => 'SchoolNurse',
                ],
                [
                    'id' => 30,
                    'designation_name' => 'Guidance Counselor',
                    'designation_id' => 'GuidanceCounselor',
                ],
                [
                    'id' => 31,
                    'designation_name' => 'Library Media Teacher',
                    'designation_id' => 'LibraryMediaTeacher',
                ],
                [
                    'id' => 32,
                    'designation_name' => 'Special Education Teacher I',
                    'designation_id' => 'SpecialEdTeacher1',
                ],
                [
                    'id' => 33,
                    'designation_name' => 'Special Education Teacher II',
                    'designation_id' => 'SpecialEdTeacher2',
                ],
                [
                    'id' => 34,
                    'designation_name' => 'Special Education Teacher III',
                    'designation_id' => 'SpecialEdTeacher3',
                ],
                [
                    'id' => 35,
                    'designation_name' => 'Learning Support Assistant',
                    'designation_id' => 'LearningSupportAssistant',
                ],
                [
                    'id' => 36,
                    'designation_name' => 'Technology Teacher',
                    'designation_id' => 'TechnologyTeacher',
                ],
                [
                    'id' => 37,
                    'designation_name' => 'Math Teacher',
                    'designation_id' => 'MathTeacher',
                ],
                [
                    'id' => 38,
                    'designation_name' => 'Science Teacher',
                    'designation_id' => 'ScienceTeacher',
                ],
                [
                    'id' => 39,
                    'designation_name' => 'English Teacher',
                    'designation_id' => 'EnglishTeacher',
                ],
                [
                    'id' => 40,
                    'designation_name' => 'Social Studies Teacher',
                    'designation_id' => 'SocialStudiesTeacher',
                ],
                [
                    'id' => 41,
                    'designation_name' => 'Filipino Teacher',
                    'designation_id' => 'FilipinoTeacher',
                ],
                [
                    'id' => 42,
                    'designation_name' => 'PE Teacher',
                    'designation_id' => 'PETeacher',
                ],
                [
                    'id' => 43,
                    'designation_name' => 'Music Teacher',
                    'designation_id' => 'MusicTeacher',
                ],
                [
                    'id' => 44,
                    'designation_name' => 'Art Teacher',
                    'designation_id' => 'ArtTeacher',
                ],
                [
                    'id' => 45,
                    'designation_name' => 'Home Economics Teacher',
                    'designation_id' => 'HomeEcTeacher',
                ],
                [
                    'id' => 46,
                    'designation_name' => 'Technology and Livelihood Education (TLE) Teacher',
                    'designation_id' => 'TLETeacher',
                ],
                [
                    'id' => 47,
                    'designation_name' => 'Agricultural Education Teacher',
                    'designation_id' => 'AgriculturalEdTeacher',
                ],
                [
                    'id' => 48,
                    'designation_name' => 'Vocational Education Teacher',
                    'designation_id' => 'VocationalEdTeacher',
                ],
                [
                    'id' => 49,
                    'designation_name' => 'Alternative Learning System (ALS) Teacher',
                    'designation_id' => 'ALSTeacher',
                ],
                [
                    'id' => 50,
                    'designation_name' => 'Education Support Services Staff',
                    'designation_id' => 'EdSupportStaff',
                ]
            
            
        ];

        // Add timestamps
        foreach ($designations as &$designation) {
            $designation['created_at'] = now();
            $designation['updated_at'] = now();
        }

        // Insert records into the database
        DB::table('designations')->insert($designations);
    }
}