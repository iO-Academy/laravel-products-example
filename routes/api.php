<?php

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Grouping routes by controller
Route::controller(ProductController::class)->group(function () {
    // REST - Representation state transfer

    // API must be stateless
    // APIs must not use session. One call to the API should not affect other calls to the API

    // We appropriately use HTTP methods GET, POST, PUT and DELETE
    // We use those methods to describe what the endpoint is doing

    // URLs should be consistent, plural and should represent the data that the end point deals with
    // PUT /products not /updateproduct

    // Responses sent by our API should use a consistent JSON format
    // An assoc array with a message and data key is a pretty common way to go
    // ['message' => 'describe what happened', 'data' => 'results go here']

    // Responses should use the appropriate HTTP status code
    // 200 - OK, it worked
    // 201 - OK, something was created (used when a POST request works)
    // 400 - bad request, the user did something dumb
    // 404 - Page not found, automatically used by laravel if you try and load a route that does not exist
    // 422 - Unprocessable content, automatically used by laravel validation
    // 500 - Something went wrong, don't really know what


    Route::get('/products', 'all');
    Route::get('/products/{id}', 'find');
    Route::post('/products', 'create');
    Route::put('/products/{id}', 'update');
    Route::delete('/products/{id}', 'delete');
});

Route::get('/blog', [BlogPostController::class, 'getAll']);
