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
}