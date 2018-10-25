<?php

namespace tests\app\Http\Controllers;

use TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
class BooksControllerTest extends TestCase{
    
    use DatabaseMigrations;
    /** @test **/
    public function index_status_code_should_be_200()
    {
        $this->get('/books')->seeStatusCode(200);
    }
    
    /** @test **/
    public function index_should_return_collection_of_records()
    {
        $books = factory('App\Book',2)->create();
        
        $this->get('/books');
        $this->seeJson(['data'=>$books->toArray()]);
    }
    
    /** @test **/
    public function show_should_return_valid_book()
    {
        $book = factory('App\Book')->create();
        $this
             ->get('/books/'.$book->id)
             ->seeStatusCode(200)
             ->seeJson( $book->toArray()
                     );
        
       
       
    }
    
    /** @test **/
    public function show_should_fail_if_book_doesnt_exist()
    {
        $this
             ->get('/books/9999',['Accept'=>"application/json"])
             ->seeStatusCode(404)
             ->seeJson([
                'status' => 404,
                'message' => 'NotFound'
            
        ]); 
    }
    
    /** @test **/
    public function show_route_must_not_match_invalid_route()
    {
        $this->get('/books/nije-broj');
        $this->assertNotRegExp('/Book not found/', $this->response->getOriginalContent(),'BooksController@show route matching when it should not.');
    }
    
    /** @test **/
    public function store_should_store_book_in_database(){
        $this->post('/books', [
            'title'=>"test knjiga",
            'description'=>"test opis",
            'author'=>"test autor"
        ]);
        
        $this->seeJson(["created"=>TRUE])->seeInDatabase('books', ["title"=>"test knjiga"]);
    }
    
    /** @test **/
    public function store_should_return_status_201_and_location_header(){
         $this->post('/books', [
            'title'=>"test knjiga",
            'description'=>"test opis",
            'author'=>"test autor"
        ]);
        $this->seeStatusCode(201)->seeHasHeaderRegExp('location', '/\/books\/[\d]+$/');
    }
    
    /** @test **/
    public function update_should_only_change_fillable_fields()
    {
        $book = factory('App\Book')->create([
           'title' => 'Ja nisam metalac',
           'author' => 'Random lik koji navija za zvezdu',
           'description' => 'Tuzna prica o liku kome je jedini kvalitet sto nije metalac'
        ]);
        
        $this->put("/books/{$book->id}", [
            'id'=>'666',
            'title'=>'Updejtovana Knjiga',
            'description'=>'updejtovani opis',
            'author'=>'updejtovani autor'
        ]);
       
        $this->seeStatusCode(200)
                ->seeJson([
                    'id'=>$book->id,
                    'title'=>'Updejtovana Knjiga',
                    'description'=>'updejtovani opis',
                    'author'=>'updejtovani autor'
                ]);
        $this->seeInDatabase('books', [
            'id'=>$book->id,
            'title'=>'Updejtovana Knjiga'
        ]);
    }
    /** @test **/
    public function update_should_fail_on_non_existing_id()
    {
        $this->put('/books/9099', [
            'id'=>'666',
            'title'=>'Updejtovana Knjiga',
            'description'=>'updejtovani opis',
            'author'=>'updejtovani autor'
        ]);
        
        $this->seeStatusCode(404)->seeJson([
            "error"=>[
                "message"=>"Book not found"
            ]
        ]);
    }
    /** @test **/
    public function update_route_must_not_match_invalid_route()
    {
        $this->put('/books/nepostojeca')->seeStatusCode(404);
    }
    /** @test **/
    public function destroy_should_remove_valid_book_and_return_204()
    {
        $book = factory('App\Book')->create();
        $this->delete('/books/'.$book->id);
        $this->seeStatusCode(204)->isEmpty();
        
        $this->notSeeInDatabase('books', ['id'=>$book->id]);
    }
    /** @test **/
    public function desstroy_should_return_404_for_non_existing_book()
    {
        $this->delete("/books/9999")->seeStatusCode(404);
        $this->seeJson([
            "error" => [
                "message"=>"Knjiga nije nadjena"
            ]
        ]);
    }
    /** @test **/
    public function destroy_should_not_match_invalid_route()
    {
        $this->delete('/books/awdawdw')->seeStatusCode(404);
    }
}   
