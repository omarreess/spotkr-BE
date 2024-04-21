<?php

namespace Modules\LeaderBoard\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LeaderBoardDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        $this->call([
            AchievementSeeder::class
        ]);
    }
}
