<?php

namespace Modules\Auth\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Auth\Enums\UserTypeEnum;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Role::create(['name' => UserTypeEnum::ADMIN]);
        $counter = 0;
        foreach (UserTypeEnum::availableTypes() as $type) {
            User::create([
                'name' => $type,
                'phone' => "20112345678$counter",
                'phone_verified_at' => now(),
                'status' => true,
                'username' => $type,
                'password' => $type == UserTypeEnum::ADMIN
                    ? "20112345678$counter"
                    : null,
                'type' => $type,
                'bio' => fake()->randomElement([null, fake()->realText()]),
                'social_links' => fake()->randomElement([null, [
                    fake()->url(),
                    fake()->url(),
                    fake()->url(),
                ]]),
            ]);

            $counter++;
        }
    }
}
