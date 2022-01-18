<?php

use PandaOreo\Tinymce\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('tinymce', Controllers\TinymceController::class . '@index');
Route::post('/file/upload', Controllers\FileUploadController::class . '@handle')->name('file.upload');
Route::post('/video/upload', Controllers\FileUploadController::class . '@uploadVideo')->name('video.upload');
