<?php

namespace Modules\Coupon\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Coupon\Entities\Coupon;

class CouponDatabaseSeeder extends Seeder
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
            $usersLimit = fake()->randomElement([null, rand(1, 100)]);
            Coupon::create([
                'code' => Str::random(10),
                'number_of_users' => $usersLimit,
                'used_by_users' => $usersLimit ? rand(0, $usersLimit) : null,
                'discount' => fake()->randomFloat(2, 1, 30),
                'status' => fake()->boolean(),
                'valid_till' => fake()->randomElement([null, fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s')]),
                'created_at' => now(),
            ]);
        }
    }
}
