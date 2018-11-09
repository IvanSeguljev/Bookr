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
            return  $this->item(Book::findOrFail($id), new BookTransformer());
    }
    
    public function Store(Request $request)
    {
        $this->validate($request, [
            'title'=>'required|max:255',
            'description'=>'required',
            'author'=>'required'
        ],
        [
            'title.required'=>'Morate uneti naslov!',
            'description.required'=>'Morate uneti opis!',
            'author.required'=>'Morate uneti autora!',
            'title.max'=>'Naslov ne sme biti duzi od 255 karaktera!'
        ]);
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
        $this->validate($req, [
            'title'=>'required|max:255',
            'description'=>'required',
            'author'=>'required'
        ],
        [
            'title.required'=>'Morate uneti naslov!',
            'description.required'=>'Morate uneti opis!',
            'author.required'=>'Morate uneti autora!',
            'title.max'=>'Naslov ne sme biti duzi od 255 karaktera!'
        ]);
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
