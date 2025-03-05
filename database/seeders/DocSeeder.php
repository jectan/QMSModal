<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('DocType')->insert([
            ['docTypeDesc' => 'Quality Manual', 'status' => true],
            ['docTypeDesc' => 'Forms', 'status' => true],
            ['docTypeDesc' => 'Template', 'status' => true],
            ['docTypeDesc' => 'Operations Manual', 'status' => true],
            ['docTypeDesc' => 'Procedures Manual', 'status' => true],
        ]);
        
        DB::table('RequestType')->insert([
            ['requestTypeDesc' => 'Creation', 'status' => true],
            ['requestTypeDesc' => 'Revision', 'status' => true],
            ['requestTypeDesc' => 'Deletion', 'status' => true],
        ]);
    }
}
