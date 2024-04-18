<?php

namespace Modules\Order\Database\factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Modules\Activity\Entities\Activity;
use Modules\Order\Enums\OrderStatusEnum;

class OrderFactory extends Factory
{
    private array $activities;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);
        $this->activities = Activity::all('id')->pluck('id')->toArray();
    }

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Order\Entities\Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'activity_id' => fake()->randomElement($this->activities),
            'user_id' => 2,
            'cost' => fake()->randomFloat(2, 0, 1000),
            'discount' => fake()->randomElement([fake()->randomFloat(2, 1, 100), null]),
            'status' => fake()->randomElement(OrderStatusEnum::toArray()),
            'adults_count' => fake()->numberBetween(1, 10),
            'children_count' => fake()->numberBetween(0, 10),
            'calendar_date' => fake()->date(),
            'user_details' => [
                'name' => fake()->name,
                'email' => fake()->email,
                'phone' => fake()->phoneNumber,
            ],
            'sessions_count' => fake()->randomElement([
                null,
                rand(1, 5),
            ]),
        ];
    }
}

