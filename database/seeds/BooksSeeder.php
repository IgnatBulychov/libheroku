<?php

use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = factory(App\Tag::class, intval(env('FACTORY_COUNT_TAGS')))->create();

        $books = factory(App\Book::class, intval(env('FACTORY_COUNT_BOOKS')))->create();

        $tags = App\Tag::all();

        App\Book::all()->each(function ($book) use ($tags) { 
            $book->tags()->attach(
                $tags->random(rand(1, 10))->pluck('id')->toArray()
            ); 
        });
    }
}
