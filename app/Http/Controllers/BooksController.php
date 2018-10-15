<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class BooksController extends BaseController
{
    public function Index()
    {
        return Book::all();
    }
    
    public function Show($id)
    {
        try{
            $book = Book::findOrFail($id);
        }
        catch (ModelNotFoundException $ex)
        {
           return response()->json([
                'error' => [
                    'message' => 'Book not found'
                 ]
            ], 404); 
        }
        return $book;
    }
}
