<?php

use App\Livewire\Article\ArticleEditor;
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

Route::view('/', 'index')->name('index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth'])->group(function(){
    Route::get('articles/new', ArticleEditor::class)->name('articles.create');
    Route::get('articles/{slug}', ArticleEditor::class)->name('articles.edit');
});

require __DIR__.'/auth.php';
