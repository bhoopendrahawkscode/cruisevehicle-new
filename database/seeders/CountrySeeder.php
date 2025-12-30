<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = config('country');
        foreach ($data as $d) {

            Country::updateOrCreate(['name' => $d['name']], $d);
        }
    }
}
