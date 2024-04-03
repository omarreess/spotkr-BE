<?php

namespace Modules\Country\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Modules\Country\Entities\City;
use Modules\Country\Entities\Country;

class CountryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $fileData = json_decode(File::get(base_path('storage/countries.min.json')), true);
        $countries = array_map(fn($countryName) => ['name' => $countryName], array_keys($fileData));
        $counter = 0;
        $cities = collect(array_map(function($cities) use (&$counter){
            $counter++;
            return array_map(fn($city) => ['name' => $city, 'country_id' => $counter], $cities);
        }, array_values($fileData)))->reduce(fn($carry, $item) => array_merge($carry, $item), []);

        Country::insert($countries);

        foreach (array_chunk($cities, 1000) as $chunk) {
            City::insert($chunk);
        }
    }
}
