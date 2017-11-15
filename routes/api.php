<?php

Route::post('/register', 'AuthController@register')->name('register');

Route::post('/login', 'AuthController@login')->name('login');

Route::post('/logout', 'AuthController@logout')->name('logout');

Route::resource('dashboard', 'DashboardController', ['except' => ['show']]);

Route::resource('note', 'NoteController', ['only' => ['update', 'destroy']]);
