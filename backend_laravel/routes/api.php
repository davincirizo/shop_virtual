<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AutenticarController;
use App\Http\Controllers\UserController;
use App\Mail\Verification;
use \Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
//Auth::routes([
//    'verify' => true,
//]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::post('registro',[AutenticarController::class,'registro']);
Route::get('email/verify/{id}/{hash}', [AutenticarController::class,'verifyEmail']);
Route::post('email/verification-notification', [AutenticarController::class,'resend_email']);
Route::post('/forgot-password', [AutenticarController::class,'forgot_password']);
Route::post('/reset-password/{token}',[AutenticarController::class,'get_forgot_password']);




//Route Categories
Route::get('categories/{category}',[CategoryController::class,'show']);
Route::post('categories',[CategoryController::class,'store']);
Route::put('categories/{category}',[CategoryController::class,'update']);
Route::get('categories/{category}',[CategoryController::class,'show']);
Route::delete('categories/{category}',[CategoryController::class,'destroy']);

//Route Products
Route::get('products/{product}',[ProductController::class,'show']);
Route::post('products',[ProductController::class,'store']);
Route::put('products/{product}',[ProductController::class,'update']);
Route::delete('products/{product}',[ProductController::class,'destroy']);
Route::get('products/category/{category}',[ProductController::class,'search']);


Route::put('profile/{user}',[UserController::class,'update']);



Route::get('email',function (){
    $response = Mail::to('rizod3409@gmail.com')->send(new Verification());
    return $response;
});


Route::group(['middleware' => ['aut']],function(){
    Route::post('logout',[AutenticarController::class,'logout']);
    Route::get('categories',[CategoryController::class,'index']);
    Route::get('products',[ProductController::class,'index']);

});

Route::group(['middleware' => ['verify-emai']],function(){
    Route::post('login',[AutenticarController::class,'login']);
    Route::post('/forgot-password', [AutenticarController::class,'forgot_password']);
});



