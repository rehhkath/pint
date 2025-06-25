<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SegmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/segments', [SegmentController::class, 'getSegments'])->name('segments.list');
