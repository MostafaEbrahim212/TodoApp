<?php
use App\Http\Controllers\user\AuthController;
use App\Http\Controllers\user\HomeController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\user\TodoController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'postRegister'])->name('postRegister');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.cover');
    Route::put('/profile/update', [ProfileController::class, 'UpdateProfile'])->name('profile.update');



    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/completed', [TodoController::class, 'completed'])->name('todos.completed');
    Route::get('/todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
    Route::post('/todos', [TodoController::class, 'create'])->name('todos.create');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::post('/todos/complete/{id}', [TodoController::class, 'complete'])->name('todos.complete');
    Route::post('/todos/incomplete/{id}', [TodoController::class, 'incomplete'])->name('todos.incomplete');
});
