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
        return ["data"=>Book::all()];
    }
    
    public function Show($id)
    {
        try{
            return  ['data'=>Book::findOrFail($id)];
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
        return response()->json(["created"=>TRUE,'data'=>$book], 201,['location'=> route('books.Show', ['id'=>$book->id])]);
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
        return response(['data'=>$book->toArray()], 200);
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
