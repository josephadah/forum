<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function () {
        			return factory(App\User::class)->create()->id;
        },
        'channel_id' => function() {
        			return factory('App\Channel')->create()->id;
        },
        'title' => $faker->sentence,
        'body' =>	$title,
        'slug' =>   str_slug($title), 
        'locked' => false
    ];
});
