<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /*
     * run database seeds
     * @return void
     */
    public function run()
    {
        DB::table('books')->insert([
        'title' => 'Gospodar prstenova',
        'description' => 'Frodo i druzina kolju saurona',
        'author' => 'H. G. Wells',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ]);
        DB::table('books')->insert([
        'title' => 'PHP Kuvar',
        'description' => 'Citate ovo i jos vam je cudno sto vas je zena ostavila?',
        'author' => 'Madeleine L\'Engle',
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now()
        ]); 
    }
}