<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager;
use App\Http\Response\FractalResponse;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\SerializerAbstract;

class FractalServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
        SerializerAbstract::class,
        DataArraySerializer::class
        );
        
        $this->app->bind(FractalResponse::class,function ($app)
        {
            $manager = new Manager();
            $serializer = $app[SerializerAbstract::class];
            
            return new FractalResponse($manager,$serializer);
        });
        $this->app->alias(FractalResponse::class, 'fractal');
    }
    public function boot()
    {
        
    }
}