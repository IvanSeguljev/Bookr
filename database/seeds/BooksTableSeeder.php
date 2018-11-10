<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BooksTableSeeder extends Seeder
{
    /*
     * run database seeds
     * @return void
     */
    public function run()
    {
        factory(\App\Author::class,10)->create()->each(function ($author){
            $bookCount = rand(1,6);
            while($bookCount>0)
            {
                $author->books()->save(factory(App\Book::class)->make());
                $bookCount--;
            }
        });
    }
}