<?php

use Faker\Generator as Faker;

$factory->define(App\Favorite::class, function (Faker $faker) {
    return [
        'user_id' => function () {
        		return factory('App\User')->create()->id;
        },
        'favorited_id' => function () {
        		return factory('App\Reply')->create()->id;
        }, 
        'favorited_type' => 'App\Reply'
    ];
});
