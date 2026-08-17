<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ArticleController;


Route::get('/', [PageController::class, 'accueil']);
Route::resource('articles', ArticleController::class);
//Route::get('/', function () { return view('welcome');});
Route::get('/bonjour', fn () => 'Bonjour Laravel');
Route::get('/bonjour/{nom}', fn (string $nom) => "Bonjour $nom");

Route::resource('articles', ArticleController::class)
    ->middleware('auth')
    ->only(['create', 'store', 'edit', 'update', 'destroy']);
