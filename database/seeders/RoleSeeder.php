<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;
use DB;

class RoleSeeder extends Seeder {
    public function run()
    {
        DB::table('roles')->delete();
        // Role::create(array('name' => 'Administrator'));
        // Role::create(array('name' => 'Data Encoder'));
    }
}
