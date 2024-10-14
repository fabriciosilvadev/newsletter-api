<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\TopicSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('auth.logout');
        Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('auth.refresh');
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'topics/subscriptions'], function () {
        Route::get('/', [TopicSubscriptionController::class, 'index'])->name('topic-subscriptions.index');
        Route::post('/', [TopicSubscriptionController::class, 'subscribe'])->name('topic-subscriptions.subscribe');
        Route::delete('/', [TopicSubscriptionController::class, 'unsubscribe'])->name('topic-subscriptions.unsubscribe');
    });
    Route::apiResource('/topics', TopicController::class);
    Route::apiResource('/posts', PostController::class);
});
