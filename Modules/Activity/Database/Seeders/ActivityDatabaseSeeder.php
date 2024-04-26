<?php

namespace Modules\Activity\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Activity\Entities\Activity;
use Modules\Activity\Enums\ActivityStatusEnum;
use Modules\Activity\Enums\ActivityTypeEnum;

class ActivityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        for ($i = 0; $i < 100; $i++) {
            $fakeType = fake()->randomElement(ActivityTypeEnum::availableTypes());
            $holdOn = in_array($fakeType, [ActivityTypeEnum::EVENT, ActivityTypeEnum::TRIP])
                ? fake()->dateTimeBetween('-1 month', '+1 month')
                : null;
            $openTimes = in_array($fakeType, [ActivityTypeEnum::COURSE, ActivityTypeEnum::SPORT])
                ? [[
                    'from' => fake()->time('H:i'),
                    'to' => fake()->time('H:i'),
                    'day' => fake()->randomElement(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
                ]]
                : null;

            Activity::create([
                'name' => fake()->name(),
                'price' => $fakeType != ActivityTypeEnum::COURSE
                    ? fake()->randomFloat(2, 1, 100)
                    : null,
                'discount' => $fakeType != ActivityTypeEnum::COURSE ? fake()->randomElement([null, rand(1, 30)]) : null,
                'type' => $fakeType,
                'description' => fake()->text(),
                'address' => fake()->randomElement([
                    null,
                    [
                        'latitude' => fake()->latitude(),
                        'longitude' => fake()->longitude(),
                        'address' => fake()->address(),
                    ],
                ]),
                'phone' => fake()->phoneNumber(),
                'fax' => fake()->phoneNumber(),
                'email' => fake()->email(),
                'website' => fake()->url(),
                'third_party_id' => 3,
                'category_id' => fake()->numberBetween(1, 10),
                'city_id' => rand(1, 1000),
                'rating_average' => fake()->randomFloat(1, 0, 5),
                'status' => fake()->randomElement(ActivityStatusEnum::availableTypes()),
                'hold_at' => $holdOn,
                'open_times' => $openTimes,
                'features' => fake()->randomElements([
                    'wifi',
                    'parking',
                    'smoking area',
                    'kids area',
                    'vip area',
                    'delivery',
                    'take away',
                    'outdoor seating',
                    'indoor seating',
                ]),
                'social_links' => fake()->randomElement([
                    null,
                    [
                        fake()->url(),
                        fake()->url(),
                        fake()->url(),
                    ],
                ]),
                'course_bundles' => $fakeType == ActivityTypeEnum::COURSE ? [[
                    'price' => fake()->randomFloat(2, 100, 500),
                    'discount' => fake()->randomElement([null, rand(1, 2)]),
                    'sessions_count' => rand(5, 15),
                ]] : null,
                'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ]);
        }
    }
}
