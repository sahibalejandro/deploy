<?php

use App\User;
use App\Database;
use Faker\Generator as Faker;

$factory->define(Database::class, function (Faker $faker, $attributes) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'name' => '_test_database',
        'user' => '_test_user'
    ];
});
