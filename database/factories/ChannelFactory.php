<?php

use Faker\Generator as Faker;

$factory->define(App\Channel::class, function (Faker $faker) {
	$word = $faker->word;
    return [
        'name' => $word,
        'slug' => $word
    ];
});
