<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UnitDivSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Division')->insert([
            ['divName' => 'Administrative and Finance Division', 'status' => true],
            ['divName' => 'Technical Operations Division', 'status' => true],
            ['divName' => 'Office of the Regional Director', 'status' => true],
        ]);

        DB::table('Unit')->insert([
            ['unitName' => 'Cybersecurity', 'divID' => '2', 'status' => true],
            ['unitName' => 'Disaster Risk Reduction Management', 'divID' => '2', 'status' => true],
            ['unitName' => 'eGovernment', 'divID' => '2', 'status' => true],
            ['unitName' => 'Free Wi-Fi For All', 'divID' => '2', 'status' => true],
            ['unitName' => 'Government Network', 'divID' => '2', 'status' => true],
            ['unitName' => 'ICT Industry Development', 'divID' => '2', 'status' => true],
            ['unitName' => 'ICT Literacy and Competency Development', 'divID' => '2', 'status' => true],
            ['unitName' => 'Management Information System Service', 'divID' => '2', 'status' => true],
            ['unitName' => 'National ICT Planning, Policy and Standard', 'divID' => '2', 'status' => true],
            ['unitName' => 'Philippine National Private Key Infrastructure', 'divID' => '2', 'status' => true],
            ['unitName' => 'Budget', 'divID' => '1', 'status' => true],
            ['unitName' => 'Finance', 'divID' => '1', 'status' => true],
            ['unitName' => 'Human Resource', 'divID' => '1', 'status' => true],
            ['unitName' => 'Records', 'divID' => '1', 'status' => true],
            ['unitName' => 'Supply', 'divID' => '1', 'status' => true],
            ['unitName' => 'ORD', 'divID' => '3', 'status' => true],
        ]);
    }
}