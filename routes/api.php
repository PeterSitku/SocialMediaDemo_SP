<?php

use App\Http\Controllers\API\FriendshipController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\PostLikeController;
use App\Http\Controllers\API\PostShareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Post
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    // Likes / Dislikes
    Route::post('/posts/{post}/like', [PostLikeController::class, 'like']);
    Route::post('/posts/{post}/dislike', [PostLikeController::class, 'dislike']);
    Route::delete('/posts/{post}/like', [PostLikeController::class, 'remove']);

    // Share
    Route::post('/posts/{id}/share', [PostShareController::class, 'share']);
    Route::delete('/posts/{id}/share', [PostShareController::class, 'unshare']);

    // Friendship
    Route::post('/friend-request/{user}', [FriendshipController::class, 'sendRequest']);
    Route::post('/friend-request/{user}/accept', [FriendshipController::class, 'acceptRequest']);
    Route::post('/friend-request/{user}/decline', [FriendshipController::class, 'declineRequest']);
    Route::get('/friends', [FriendshipController::class, 'friendsList']);
});
