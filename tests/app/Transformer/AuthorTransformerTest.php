<?php

namespace tests\app\Http\Controllers;

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Transformer\AuthorTransformer;
use League\Fractal\TransformerAbstract;
use TestCase;
use Carbon\Carbon;
use App\Author;

class AuthorTransformerTest extends TestCase
{
    use DatabaseMigrations;
    
    
    public function setUp()
    {
        Carbon::setTestNow(Carbon::now());
        parent::setUp();
        $this->subject = new AuthorTransformer();
        
    }
    
    public function tearDown()
    {
        parent::tearDown();
        Carbon::setTestNow();
    }
    
    /** @test **/
    public function it_can_be_instantiated()
    {
       
        $this->assertInstanceOf(TransformerAbstract::class,$this->subject);
    }
    /** @test **/
    public function it_transforms_author_model()
    {
        $author = factory(Author::class)->create();
        
        $result = $this->subject->transform($author);
        
        $this->assertArrayHasKey('id',$result);
        $this->assertArrayHasKey('name',$result);
        $this->assertArrayHasKey('biography',$result);
        $this->assertArrayHasKey('gender',$result);
        $this->assertArrayHasKey('created',$result);
        $this->assertArrayHasKey('updated',$result);
        
        $this->assertEquals($author->created_at->toIso8601String(),$result['created']);
        $this->assertEquals($author->updated_at->toIso8601String(),$result['updated']);
        
    }
}