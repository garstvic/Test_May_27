<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TaskPriority;
use Faker\Generator as Faker;

$factory->define(TaskPriority::class, function (Faker $faker) {
    return [
        'priority_id'=>$faker->randomElement(App\Priority::all())->id
    ];
});
