<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RAGController;

Route::get('/', function () {
    return view('rag.chat');
});

Route::get('/rag/upload', [RAGController::class, 'uploadPage'])->name('rag.upload.page');
Route::post('/rag/upload', [RAGController::class, 'upload'])->name('rag.upload');

Route::get('/rag/chat', [RAGController::class, 'chatPage'])->name('rag.chat.page');
Route::post('/rag/chat', [RAGController::class, 'chat'])->name('rag.chat');
