<?php

use App\User;
use App\Note;
use App\Panel;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    static $password;

    return [
        'login'     => $faker->unique()->name,
        'password'  => $password ?: $password = bcrypt('123456'),
        'api_token' => str_random(60),
    ];
});

$factory->define(Panel::class, function (Faker $faker) {

    return [
        'user_id' => User::all()->random()->id,
        'title'   => $faker->word
    ];
});

$factory->define(Note::class, function (Faker $faker) {

    return [
        'panel_id' => Panel::all()->random()->id,
        'checked'  => $faker->randomElement([true, false]),
        'content'  => $faker->text(20)
    ];
});
