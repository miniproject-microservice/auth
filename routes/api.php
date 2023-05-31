<?php

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'login'])->name('login');

    //Route::prefix('user/')->name('user.')->group(function () {
        
        Route::prefix('gateway/')->name('gateway.')->group(function () {
            Route::group(['middleware' => 'auth:api'], function(){
                Route::get('index', [AuthController::class, 'index']);
                
                Route::prefix('category/')->name('category.')->group(function () {
                    Route::get('index', function (Request $request) {
                        $http = new GuzzleHttp\Client;
                    
                        $client = new Client(); //GuzzleHttp\Client
                        $url = "http://127.0.0.1:8000/api/category/index";
                        $response = $client->request('GET', $url, [
                            'verify'  => false,
                        ]);
                        $category = json_decode($response->getBody());
                        return response()->json($category);
                    });

                    Route::post('store', function (Request $request) {
                        $response = Http::post('http://127.0.0.1:8000/api/category/store', [
                            'cat_name' => $request->cat_name,
                        ]);
                        return response()->json(['message' => 'new category has been created']);
                    });

                    Route::post('update/{id}', function ($id, Request $request) {
                        $response = Http::post('http://127.0.0.1:8000/api/category/update/'. $id, [
                            'cat_name' => $request->cat_name,
                        ]);
                        return response()->json(['message' => 'Category has been updated']);
                    });

                    Route::delete('delete/{id}', function ($id, Request $request) {
                        $response = Http::delete('http://127.0.0.1:8000/api/category/delete/'.$id);
                        return response()->json(['message' => 'Category has been deleted']);
                    });
                });
            });
        });

    //});



