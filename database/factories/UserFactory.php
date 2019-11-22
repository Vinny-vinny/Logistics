<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
       return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'code_no' => $faker->numberBetween(1,1000),
        'title' => $faker->word,
        'join_date' => now(),
        'email_verified_at' => now(),
        'password' => bcrypt('Qwerty123!'), // password
        'remember_token' => Str::random(10),
    ];
});
