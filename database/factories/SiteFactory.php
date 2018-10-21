<?php

use App\User;
use App\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'name' => 'Test Site',
        'git_platform' => 'github',
        'repository' => 'user/repository',
    ];
});
