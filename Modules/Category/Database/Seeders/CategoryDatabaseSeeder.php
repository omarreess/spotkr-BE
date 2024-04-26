<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Category\Entities\Category;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //TODO add parent categories
        Category::create(['name' => 'event']);
        Category::create(['name' => 'trip']);
        $course = Category::create(['name' => 'course']);
        $sport = Category::create(['name' => 'sport']);
        $subCategories = [
            'water' => [
                'Scuba diving',
                'kayak',
                'Rowing',
                'snorkeling',
                'wind surfing',
                'kite surfing',
                'sailing',
                'jet skiing',
                'parasailing',
                'water skiing',
            ],
            'desert' => [
                'sand boarding',
                '4x4 desert safari',
                'camel trekking',
                'horse riding',
                'hot air ballooning',
                'desert camping',
                'off road motorcycling',
                'dune bashing',
                'quad biking',
            ],
            'wellness' => [
                'yoga',
                'meditation',
                'retreats',
                'holistic wellness',
            ],
            'indoor' => [
                'sky diving indoor',
                'climbing indoor',
                'shooting',
                'trampolining',
            ],
            'sky' => [
                'sky diving',
                'hot air ballooning',
                'parachuting',
            ],
        ];

        foreach ($subCategories as $key => $subCategory) {
            $parent = Category::create(['name' => "$key sport", 'parent_id' => $sport->id]);
            foreach ($subCategory as $value) {
                Category::create(['name' => "$value sport", 'parent_id' => $parent->id]);
            }
        }

        foreach ($subCategories as $key => $subCategory) {
            $parent = Category::create(['name' => "$key sport course", 'parent_id' => $course->id]);
            foreach ($subCategory as $value) {
                Category::create(['name' => "$value sport course", 'parent_id' => $parent->id]);
            }
        }
    }
}
