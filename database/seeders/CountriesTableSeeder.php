<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Nakanakaii\Countries\Countries;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        // Get all countries from the package
        $countries = Countries::all();
        
        // Transform the data for database insertion
        $data = [];
        foreach ($countries as $country) {
            $data[] = [
                'name' => $country['name'],
                'flag' => $country['flag'],
                'code' => $country['code'],
                'dial_code' => $country['dialCode'],
                'region_code' => $country['regionCode'] ?? null,
                'timezones' => json_encode($country['timezones'] ?? []),
                'languages' => json_encode($country['languages'] ?? []),
                'language_codes' => json_encode($country['languageCodes'] ?? []),
                'region' => $country['region'] ?? null,
                'min_length' => $country['minLength'],
                'max_length' => $country['maxLength'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert into database
        DB::table('countries')->insert($data);
    }
}
