<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('guessSongs', [App\Http\Controllers\TrackController::class, 'guessSongs'])->name('guessSongs');
Route::get('guessMovies', [App\Http\Controllers\MovieController::class, 'guessMovies'])->name('guessMovies');
Route::get('guessCars', [App\Http\Controllers\CarController::class, 'guessCars'])->name('guessCars');

Route::get('load', [App\Http\Controllers\PictureController::class, 'load'])->name('load');

Route::get('loadTracks', [App\Http\Controllers\TrackController::class, 'loadTracks'])->name('loadTracks');
Route::get('saveTrackInfo', [App\Http\Controllers\TrackController::class, 'saveTrackInfo'])->name('saveTrackInfo');

Route::get('loadMovies', [App\Http\Controllers\MovieController::class, 'loadMovies'])->name('loadMovies');
Route::get('saveMovieInfo', [App\Http\Controllers\MovieController::class, 'saveMovieInfo'])->name('saveMovieInfo');

Route::get('loadCars', [App\Http\Controllers\CarController::class, 'loadCars'])->name('loadCars');
Route::get('saveCarInfo', [App\Http\Controllers\CarController::class, 'saveCarInfo'])->name('saveCarInfo');


Route::resource('tracks', App\Http\Controllers\TrackController::class);
Route::resource('movies', App\Http\Controllers\MovieController::class);
Route::resource('cars', App\Http\Controllers\CarController::class);



Route::resource('pictures', App\Http\Controllers\PictureController::class);



Route::get('play', [App\Http\Controllers\PlayController::class, 'play'])->name('play');
Route::post('setRarity', [App\Http\Controllers\CardController::class, 'setRarity'])->name('setRarity');
Route::resource('cards', App\Http\Controllers\CardController::class);


Route::get('getRandomCards/{numberOfPlayers}', [App\Http\Controllers\CardController::class, 'getRandomCards'])->name('getRandomCards');


require __DIR__.'/auth.php';
