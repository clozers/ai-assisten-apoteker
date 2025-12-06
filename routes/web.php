<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RAGController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;

Route::get('/', function () {
    return view('rag.chat');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Semua route RAG hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {

    Route::get('/rag/upload', [RAGController::class, 'uploadPage'])
        ->name('rag.upload.page');

    Route::post('/rag/upload', [RAGController::class, 'upload'])
        ->name('rag.upload');

    // MANAGEMENT DATASET (pakai tabel documents)
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
});


Route::get('/rag/chat', [RAGController::class, 'chatPage'])
    ->name('rag.chat.page');

Route::post('/rag/chat', [RAGController::class, 'chat'])
    ->name('rag.chat');

// Logout Route opsional
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
