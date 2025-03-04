<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use App\Models\Office;
use App\Models\Role;

use Illuminate\Support\Facades\Hash;
use DB;


class AccountSeeder extends Seeder {
    public function run()
    {
        DB::table('users')->delete();
        DB::table('staff')->delete();
        DB::table('offices')->delete();
        $password = Hash::make('jec');
        $Role =  Role::create(array('name' => 'Administrator'));
        Role::create(array('name' => 'Call Agent'));
        Role::create(array('name' => 'Admin Staff'));
        Role::create(array('name' => 'Office Staff'));

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
       
        $User =   User::create(array('username' => 'SuperAdmin', 'password' => $password, 'role_id' => $Role->id));
        $Office = Office::create(array('name' => "City Mayor's Office - Computer Services Division"));
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

        Staff::create(array('firstname' => 'Super', 'lastname' => 'Admin','user_id' => $User->id, 'office_id' => $Office->id,'role_id' => $Role->id));
    }
}
