<?php
use App\Models\Surah;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;

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
Route::get('/clear-cache', function() {
    Artisan::call('view:cache');
    return "Cache is cleared";
});
Route::get('/topics', function () {
  
    return view('topics');
});
Route::get('/subtopics', function () {
  
    return view('subtopics');
});
Route::get('/search', function () {
  
    return view('search');
});

Route::prefix('api')->group(function ()
{
   Route::post('fetch-ayah', [ClientController::class, 'fetchSurahAyah']);

});
Route::get('/ayah/{id}', [ClientController::class, 'fetchAyah']);

Route::get('/result', [ClientController::class, 'elasticsearchQueries']);

Route::prefix('dashboard')->group(function ()
{
   Route::get('topicsBubble', [DashboardController::class, 'topicsBubble']);
   Route::get('subtopicsBubble', [DashboardController::class, 'subtopicsBubble']);
});