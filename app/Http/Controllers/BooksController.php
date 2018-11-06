<?php

namespace App\Http\Controllers;


use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Transformer\BookTransformer;

class BooksController extends Controller
{
    public function Index()
    {
        return $this->collection(Book::all(), new BookTransformer);
    }
    
    public function Show($id)
    {
        try{
            return  $this->item(Book::findOrFail($id), new BookTransformer());
        }
        catch (ModelNotFoundException $ex)
        {
           return response()->json([
                'error' => [
                    'message' => 'Not Found',
                    'status'=> 404
                 ]
            ], 404); 
        }
        
    }
    
    public function Store(Request $request)
    {
        
        $book = Book::create($request->all()); 
        $data = $this->item($book, new BookTransformer);
        return response()->json($data, 201,['location'=> route('books.Show', ['id'=>$book->id])]);
    }
    
    public function Update(Request $req,$id){
        try
        {
            $book = Book::findOrFail($id);
        }
        catch (ModelNotFoundException $ex)
        {
            return response()->json([
            "error"=>[
                "message"=>"Not Found"
                ]
            ], 404);
        }
        $book->fill($req->all());
        $book->save();
        return response($this->item($book,new BookTransformer), 200);
    }
    
    public function Delete($id)
    {
        try
        {
        $book = Book::findOrFail($id);
        }
        catch (ModelNotFoundException $ex)
        {
            return response()->json([
                "error" => [
                    "message"=>"Not Found"
            ]
            ], 404);
        }
        $book->delete();
        return response(NULL, 204);
    }
}
