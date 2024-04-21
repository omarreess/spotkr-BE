<?php

namespace Modules\LeaderBoard\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\LeaderBoard\Entities\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $achievements = [
            ['title' => 'Sports Rookie', 'required_points' => 100, 'user_id' => 2],
            ['title' => 'Active Adventurer', 'required_points' => 15, 'user_id' => 2],
            ['title' => 'Sports Enthusiast', 'required_points' => 30, 'user_id' => 2],
            ['title' => 'Athletic Achiever', 'required_points' => 50, 'user_id' => 2],
            ['title' => 'Sporting Explorer', 'required_points' => 75, 'user_id' => 2],
            ['title' => 'Sports Prodigy', 'required_points' => 100, 'user_id' => 2],
            ['title' => 'Elite Athlete', 'required_points' => 140, 'user_id' => 2],
            ['title' => 'Sporting Legend', 'required_points' => 200, 'user_id' => 2],
            ['title' => 'Champion of Sports', 'required_points' => 300, 'user_id' => 2]
        ];

        Achievement::insert($achievements);
    }
}
