<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BooksController extends BaseController
{
    public function Index()
    {
        return Book::all();
    }
    
    public function Show($id)
    {
        try{
            return  Book::findOrFail($id);
        }
        catch (ModelNotFoundException $ex)
        {
           return response()->json([
                'error' => [
                    'message' => 'Book not found'
                 ]
            ], 404); 
        }
        
    }
    
    public function Store(Request $request)
    {
        
        $book = Book::create($request->all()); 
        return response()->json(["created"=>TRUE], 201);
    }
}
