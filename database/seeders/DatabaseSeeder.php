<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Activity\Database\Seeders\ActivityDatabaseSeeder;
use Modules\Auth\Database\Seeders\UserSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Country\Database\Seeders\CountryDatabaseSeeder;
use Modules\Coupon\Database\Seeders\CouponDatabaseSeeder;
use Modules\LeaderBoard\Database\Seeders\LeaderBoardDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            CountryDatabaseSeeder::class,
            CategoryDatabaseSeeder::class,
            CouponDatabaseSeeder::class,
            ActivityDatabaseSeeder::class,
            OrderDatabaseSeeder::class,
            LeaderBoardDatabaseSeeder::class,
        ]);
    }
}
