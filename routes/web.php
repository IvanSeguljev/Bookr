<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//Book Controller routes
$router->get('/', function () use ($router) {
    return $router->app->version();
});
$router->get("/books", 'BooksController@Index');

$router->get("/books/{id:[\d]+}",['uses'=>'BooksController@Show','as'=>'books.Show']);

$router->post("/books","BooksController@Store");

$router->put("/books/{id:[\d]+}",'BooksController@Update');

$router->delete("/books/{id:[\d]+}",'BooksController@Delete');

$router->group([
    'prefix'=>'/authors',
    'namespace'=>'App\Http\Controllers'
],function (\Laravel\Lumen\Routing\Router $app){
    $app->get('/','AuthorsController@index');
    $app->post('/','AuthorsController@store');
    $app->get('/{id:[\d]+}','AuthorsController@show');
    $app->put('/{id:[\d]+}','AuthorsController@update');
    $app->delete('/{id:[\d]+}','AuthorsController@destroy');
});