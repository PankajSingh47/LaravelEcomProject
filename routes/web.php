<?php

use Illuminate\Support\Facades\Route;
// here we have imported controllers for using them in routes
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;


 // here we have made all the route and their controller function so when a user click then how it works we  have defined here
Route::get('/signin', function () {
    return view('signin');
});
Route::get('/signout', function () {
    Session::forget('user');
    return redirect('/signin');
});
Route::view("/signup","signup");
Route::post("signup",[UserController::class,'signup']);

Route::get("cartlist",[ProductController::class,'cartList']);
Route::get("removecart/{id}",[ProductController::class,'removeCart']);
Route::get("ordernow",[ProductController::class,'orderNow']);
Route::post("orderplace",[ProductController::class,'orderPlace']);


Route::post('/signin',[UserController::class,'signin']);
Route::get('/',[ProductController::class,'index']);
Route::get('/detail/{id}',[ProductController::class,'detail']);
Route::post('/add_to_cart',[ProductController::class,'addToCart']);
Route::get("myorder",[ProductController::class,'myOrder']);
