<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AutenticarController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::resource('admin',CategoryController::class)->names('admin.categories');

// Route::apiResource('autenticar',AutenticarController::class)->names('autenticar');
Route::post('registro',[AutenticarController::class,'registro']);
Route::post('login',[AutenticarController::class,'login']);

Route::apiResource('categories',CategoryController::class);

Route::apiResource('products',ProductController::class);




Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::post('logout',[AutenticarController::class,'logout']);

});





// Route::get('admin/categories',[CategoryController::class,'index']);

