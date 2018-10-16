<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
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
}
