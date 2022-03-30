<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CommentsController;

Route::get('comments', [CommentsController::class, 'index']);
Route::post('comment', [CommentsController::class, 'store'])->middleware('ensure.comments.are.three.levels.deep.only');

