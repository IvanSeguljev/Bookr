<?php

namespace tests\app\Transformer;

use TestCase;
use App\Book;
use App\Transformer\BookTransformer;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class BookTransformerTest extends TestCase{
    use DatabaseMigrations;
    
    /** @test **/
    public function it_can_be_initialized()
    {
        $subject = new BookTransformer();
        $this->assertInstanceOf(TransformerAbstract::class,$subject);
    }
    
    /** @test **/
    public function it_transforms_book_model()
    {
        $book = $this->bookFactory();
        $subject = new BookTransformer();
        $transform = $subject->transform($book);
        
        $this->assertArrayHasKey("id",$transform);
        $this->assertArrayHasKey("title",$transform);
        $this->assertArrayHasKey('description',$transform);
        $this->assertArrayHasKey('author',$transform);
        $this->assertArrayHasKey('created',$transform);
        $this->assertArrayHasKey('updated',$transform);
        $this->assertEquals($book->created_at->toIso8601String(),$transform['created']);
        $this->assertEquals($book->updated_at->toIso8601String(),$transform['updated']);
    }
}