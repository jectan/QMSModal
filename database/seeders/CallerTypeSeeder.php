<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\CallerType;
use DB;

class CallerTypeSeeder extends Seeder {
    public function run()
    {
        DB::table('caller_types')->delete();
        CallerType::create(array('name' => 'Complaint'));
        CallerType::create(array('name' => 'Request'));
        CallerType::create(array('name' => 'Inquiry')); 
        CallerType::create(array('name' => 'Concern'));
        CallerType::create(array('name' => 'Others'));
    }
}
