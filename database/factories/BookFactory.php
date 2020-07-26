<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(2),
        'cover' => $faker->imageUrl(),
        'authors' => $faker->name,
        'year' => $faker->year
    ];
});
