<?php

namespace Modules\LeaderBoard\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class LeaderBoardDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call([
            AchievementSeeder::class,
        ]);
    }
}
