<?php
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Illuminate\Database\Eloquent\Model;
use App\Author;
use App\Book;
abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    use MockeryPHPUnitIntegration;
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
    public function seeHasHeader($header)
    {
        $this->assertTrue(
        $this->response->headers->has($header),"Header pod nazivom {$header} ne postoji"
                );
        return $this;
    }
    
    public function seeHasHeaderRegExp($header,$regExp)
    {
        $this->seeHasHeader($header);
        $this->assertRegExp($regExp,$this->response->headers->get($header));
        return $this;
    }
    
    protected function bookFactory($count = 1)
    {
        $author = factory(\App\Author::class)->create();
        
        $books = factory(\App\Book::class,$count)->make();
        
        if($count == 1)
        {
            $books = $books[0];
            $books->author()->associate($author);
            $books->save();
        }
        else
        {
            $books->each(function ($book) use ($author){
                $book->author()->associate($author);
                $book->save();
            });
        }
        return $books;
    }
}
