<?php

namespace tests\app\Http\Controllers;

use TestCase;

class BooksControllerTest extends TestCase{
    
    /** @test **/
    public function index_status_code_should_be_200()
    {
        $this->get('/books')->seeStatusCode(200);
    }
    
    /** @test **/
    public function index_should_return_collection_of_records()
    {
        $this->get('/books')->seeJson(['title'=>'Gospodar prstenova'])->seeJson(['title'=>'PHP Kuvar']);
    }
    
    /** @test **/
    public function show_should_return_valid_book()
    {
        $this
             ->get('/books/1')
             ->seeStatusCode(200)
             ->seeJson([
                     'title' => 'Gospodar prstenova',
                     'description' => 'Frodo i druzina kolju saurona',
                     'author' => 'H. G. Wells',
                     ]);
        
        $data = json_decode($this->response->getOriginalContent(),TRUE);
        $this->assertArrayHasKey('created_at',$data);
        $this->assertArrayHasKey('updated_at',$data);
    }
    
    /** @test **/
    public function show_should_fail_if_book_doesnt_exist()
    {
        $this
             ->get('/books/9999')
             ->seeStatusCode(404)
             ->seeJson([
                'error' => [
                'message' => 'Book not found'
            ]
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
}   
