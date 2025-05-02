<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use App\Models\Office;
use App\Models\Unit;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use DB;


class AccountSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();
        DB::table('staff')->delete();
        DB::table('offices')->delete();
        $password = Hash::make('*1234#');
        $Role =  Role::create(array('name' => 'Administrator'));
        Role::create(array('name' => 'Document Management Team'));
        Role::create(array('name' => 'Regional Director'));
        Role::create(array('name' => 'Quality Management Representative'));
        Role::create(array('name' => 'Staff'));

        Office::create(array('name' => "City Accountant's Office"));
        Office::create(array('name' => "City Administrator's Office"));
        Office::create(array('name' => "City Agriculture Office"));
        Office::create(array('name' => "City Assessor Office"));
        Office::create(array('name' => "City Budget Office"));
        Office::create(array('name' => "City Civil Registrar Office"));
        Office::create(array('name' => "City Disaster and Risk Reduction Management Office"));
        Office::create(array('name' => "City Environment and Natural Resources Office"));
        Office::create(array('name' => "City Engineer's Office"));
        Office::create(array('name' => "City General Services Office"));
        Office::create(array('name' => "City Health Office"));
        Office::create(array('name' => "City Human Resource Management Office"));
        Office::create(array('name' => "City Legal Office"));
        Office::create(array('name' => "City Mayor's Office"));
        Office::create(array('name' => "City Mayor's Office - Barangay Affairs"));
        Office::create(array('name' => "City Mayor's Office - Bids and Awards Committee"));
        Office::create(array('name' => "City Mayor's Office - Business and Permit Licensing Office"));
       
        // $User =   User::create(array('username' => 'SuperAdmin', 'password' => $password, 'role_id' => '1'));
        // $Unit = Unit::create(array('unitName' => "Management Information Systems Service", 'divID' => '1', 'status' => '1'));
        Office::create(array('name' => "City Mayor's Office - GAD"));
        Office::create(array('name' => "City Mayor's Office - Housing and Land Management Division"));
        Office::create(array('name' => "City Mayor's Office - Internal Audit Unit"));
        Office::create(array('name' => "City Mayor's Office - Investment"));
        Office::create(array('name' => "City Mayor's Office - Museum")); 
        Office::create(array('name' => "City Mayor's Office - Personal Staff")); 
        Office::create(array('name' => "City Mayor's Office - Protected Area Management Unit"));
        Office::create(array('name' => "City Mayor's Office - Public Information Office"));
        Office::create(array('name' => "City Mayor's Office - Secretary to the Mayor"));
        Office::create(array('name' => "City Mayor's Office - Security and Surveillance Coordinating Office"));
        Office::create(array('name' => "City Mayor's Office - Sports Development Office")); 
        Office::create(array('name' => "City Mayor's Office - Tourism Promotions Development Services Division"));
        Office::create(array('name' => "City Planning and Development Office"));
        Office::create(array('name' => "City Secretary / Sanguniang Panglungsod"));
        Office::create(array('name' => "City Social Welfare and Development Office"));
        Office::create(array('name' => "City Treasurer Office"));
        Office::create(array('name' => "City Veterinarian Office"));

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
