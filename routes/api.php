<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EventController;

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

// o parêmetro uuid está definido como padrão no getRouteKeyName da classe Models\Event
Route::get('event/client/{event}', [EventController::class, 'client'])->whereUuid('event');
Route::get('event/admin/{event:uuid_admin}', [EventController::class, 'admin'])->whereUuid('event');
