<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use App\Models\Unit;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use DB;


class AccountSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();
        DB::table('staff')->delete();
        $password = Hash::make('*1234#');
        $Role =  Role::create(array('name' => 'Administrator'));
        Role::create(array('name' => 'Document Management Team'));
        Role::create(array('name' => 'Regional Director'));
        Role::create(array('name' => 'Quality Management Representative'));
        Role::create(array('name' => 'Staff'));

        $User =   User::create(array('username' => 'kryztle.evangelista@dict.gov.ph', 'password' => $password, 'role_id' => '1', 'isNew' => '0'));
        Staff::create(array('firstname' => 'Kryztle Love', 'middlename' =>'N.', 'lastname' => 'Evengelista','user_id' => '1', 'unitID' => '8'));
        $User =   User::create(array('username' => 'almark.ramos@dict.gov.ph', 'password' => $password, 'role_id' => '2', 'isNew' => '0'));
        Staff::create(array('firstname' => 'Almark', 'middlename' =>'G.', 'lastname' => 'Ramos','user_id' => '2', 'unitID' => '14'));
        $User =   User::create(array('username' => 'cheryl.ortega@dict.gov.ph', 'password' => $password, 'role_id' => '3', 'isNew' => '0'));
        Staff::create(array('firstname' => 'Cheryl', 'middlename' =>'C.', 'lastname' => 'Ortega','user_id' => '3', 'unitID' => '16'));
        $User =   User::create(array('username' => 'aris.austria@dict.gov.ph', 'password' => $password, 'role_id' => '4', 'isNew' => '0'));
        Staff::create(array('firstname' => 'Aris', 'middlename' =>'B.', 'lastname' => 'Austria','user_id' => '4', 'unitID' => '17'));
        $User =   User::create(array('username' => 'jerico.tan@dict.gov.ph', 'password' => $password, 'role_id' => '5', 'isNew' => '0'));
        Staff::create(array('firstname' => 'Jerico', 'middlename' =>'B.', 'lastname' => 'Tan','user_id' => '5', 'unitID' => '7'));
        $User =   User::create(array('username' => 'miss.region9basulta@dict.gov.ph', 'password' => $password, 'role_id' => '1', 'isNew' => '0'));
        Staff::create(array('firstname' => 'MISS', 'middlename' =>'Team', 'lastname' => 'Region IX & BASULTA','user_id' => '6', 'unitID' => '8'));
    }
}
