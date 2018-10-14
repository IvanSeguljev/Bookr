<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Book;
class BooksController extends BaseController
{
    public function Index()
    {
        return Book::all();
    }
}
