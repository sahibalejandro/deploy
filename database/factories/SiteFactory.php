<?php

use App\Site;
use Faker\Generator as Faker;

$factory->define(Site::class, function (Faker $faker) {
    return [
        'name' => 'Test Site',
        'git_platform' => 'github',
        'repository' => 'user/repository',
    ];
});
