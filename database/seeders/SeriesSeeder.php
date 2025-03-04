<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;
use DB;

class SeriesSeeder extends Seeder
{
    public function run()
    {
        DB::table('series')->delete();
        Series::create(array(
            'name' => 'Ticket',
            'slug' => 'ticket',
            'prefix' => 'e',
            'max_digit' => 6,
            'starting_value' => 1));
    }
}
