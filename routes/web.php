<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\User\UploadPhoto;
use App\Http\Controllers\Admin\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/upload', UploadPhoto::class)->name('upload.photo.user');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('setlocale/{locale}',function($lang){
    \Session::put('locale',$lang);
    return redirect()->back();
});

Route::group(['middleware'=> 'language'],function ()
{
    // Route::get('/', function () {
    //     return view('index');
    // });

    //Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    //    return view('admin.dashboard');
    //})->name('dashboard');

    Route::middleware(['auth:sanctum', 'verified'])->group(function(){
        Route::resource('admin/events', EventController::class)->names('event');
    });

});
