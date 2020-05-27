<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TaskStatus;
use Faker\Generator as Faker;

$factory->define(TaskStatus::class, function (Faker $faker) {
    return [
        'status_id'=>$faker->randomElement(App\Status::all())->id
    ];
});
