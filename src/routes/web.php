<?php

use Framework\Routing\Route;
use src\controllers\LoginController;

Route::get('/login', [LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/', [LoginController::class, 'home']);
Route::get('/home', [LoginController::class, 'home']);
Route::post('/home', [LoginController::class, 'login']);
Route::get('/auth', [LoginController::class, 'auth']);
Route::post('/auth', [LoginController::class, 'auth']);

Route::runRoute();
