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

$router->get("/books/{id:[\d]+}",'BooksController@Show');

$router->post("/books","BooksController@Store");