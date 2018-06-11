<?php

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

$factory->define(App\Image::class, function (Faker $faker) {
	
    return [
        'images' => $faker->imageUrl($width = 640, $height = 480),
        'gallery_id' => $faker->numberBetween($min = 1, $max = 25)
      
    ];
});
