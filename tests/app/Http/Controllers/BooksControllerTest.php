<?php

namespace tests\app\Http\Controllers;

use TestCase;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Carbon\Carbon;
use Illuminate\Http\Response;
class BooksControllerTest extends TestCase{
    
    public function setUp()
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::now());
    }
    public function tearDown() {
        parent::tearDown();
        
        Carbon::setTestNow();
    }


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
        $content = $this->response->getOriginalContent();
        
        
        foreach ($books as $book)
        {
            $this->seeJson([
                'id' =>$book->id,
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'created' => $book->created_at->toIso8601String(),
                'updated' => $book->updated_at->toIso8601String()
            ]);
        }
    }
    
    /** @test **/
    public function show_should_return_valid_book()
    {
        $book = factory('App\Book')->create();
        
        $this->get("/books/" . $book->id);
        
        $content = $this->response->getOriginalContent();
        $data = $content['data'];
        
        $this->assertArrayHasKey("data",$content);
        
        $this->assertEquals($book->id,$data['id']);
        $this->assertEquals($book->author,$data['author']);
        $this->assertEquals($book->description,$data['description']);
        $this->assertEquals($book->title,$data['title']);
        $this->assertEquals($book->created_at->toIso8601String(),$data['created']);
        $this->assertEquals($book->updated_at->toIso8601String(),$data['updated']);
        
       
       
    }
    
    /** @test **/
    public function show_should_fail_if_book_doesnt_exist()
    {
        $this
             ->get('/books/9999',['Accept'=>"application/json"])
             ->seeStatusCode(404)
             ->seeJson([
                'status' => 404,
                'message' => 'Not Found'
            
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
        
        $body = $this->response->getOriginalContent();
        $this->assertArrayHasKey("data",$body);
        
        $data = $body['data'];
        $this->assertEquals("test knjiga",$data['title']);
        $this->assertEquals('test opis',$data['description']);
        $this->assertEquals("test autor",$data['author']);
        $this->assertTrue($data['id']>0,"Id mora biti veci od 0 !!!");
        $this->assertEquals(Carbon::now()->toIso8601String(),$data['created']);
        $this->assertEquals(Carbon::now()->toIso8601String(),$data['created']);
        
        
        $this->seeInDatabase('books', ["title"=>"test knjiga"]);
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
    public function it_validates_required_fields_when_storing_a_book()
    {
        $this->post('/books', [],['Accept'=>'application/json']);
        
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->response->getStatusCode());
        
        $body = $this->response->getOriginalContent();
        
        $this->assertArrayHasKey('title',$body);
        $this->assertArrayHasKey('author',$body);
        $this->assertArrayHasKey('description',$body);
        
        $this->assertEquals('The title field is required.',$body['title'][0]);
        $this->assertEquals('The author field is required.',$body['author'][0]);
        $this->assertEquals('The description field is required.',$body['description'][0]);
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
        
        $body = $this->response->getOriginalContent();
        $this->assertArrayHasKey('data',$body);
        $this->assertArrayHasKey('updated',$body['data']);
        $this->assertArrayHasKey('created',$body['data']);
        $this->assertEquals(Carbon::now()->toIso8601String(),$body['data']['created']);
        $this->assertEquals(Carbon::now()->toIso8601String(),$body['data']['updated']);
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
                "message"=>"Not Found"
            ]
        ]);
    }
    /** @test **/
    public function update_route_must_not_match_invalid_route()
    {
        $this->put('/books/nepostojeca')->seeStatusCode(404);
    }
    
    /** @test **/
    public function it_validates_passed_fields_when_updating_a_book()
    {
        $book = factory(\App\Book::class)->create();
        
        $this->put("/books/{$book->id}", [],["Accept"=>"application/json"]);
        
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY,$this->response->getStatusCode());
        
        $data = $this->response->getOriginalContent();
        
        $this->assertArrayHasKey("title",$data);
        $this->assertArrayHasKey("author",$data);
        $this->assertArrayHasKey("description",$data);
        
        $this->assertEquals("The title field is required.",$data['title'][0]);
        $this->assertEquals("The author field is required.",$data['author'][0]);
        $this->assertEquals("The description field is required.",$data['description'][0]);
        
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
                "message"=>"Not Found"
            ]
        ]);
    }
    /** @test **/
    public function destroy_should_not_match_invalid_route()
    {
        $this->delete('/books/awdawdw')->seeStatusCode(404);
    }
}   
