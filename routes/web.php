<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/posts', function () {
    return view('dashboard');
})->name('posts.index');

Route::get('/posts/pending', function () {
    return view('dashboard');
})->name('posts.pending');
