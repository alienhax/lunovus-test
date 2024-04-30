<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});
*/

use App\Http\Controllers\Controller;

Route::get('/', [Controller::class, 'index']);
Route::get('/get_github_user',[Controller::class, 'get_github_user']);
Route::get('/get_github_user_followers',[Controller::class, 'get_github_user_followers']);