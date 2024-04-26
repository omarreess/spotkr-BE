<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Order\Entities\Order;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Order::factory(100)->create();
        // $this->call("OthersTableSeeder");
    }
}
