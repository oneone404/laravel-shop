<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\GameCategoryController;
use App\Http\Controllers\Seller\GameAccountController;
use App\Http\Controllers\Seller\RandomAccountController;
use App\Http\Controllers\Seller\HistoryController;

Route::prefix('seller')
    ->name('seller.')
    ->middleware(['web', 'auth', 'seller'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/', [DashboardController::class, 'index'])->name('dashboard.filter');

        // --- Danh mục game ---
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [GameCategoryController::class, 'index'])->name('index');
            Route::get('/create', [GameCategoryController::class, 'create'])->name('create');
            Route::post('/store', [GameCategoryController::class, 'store'])->name('store');
            Route::get('/edit/{category}', [GameCategoryController::class, 'edit'])->name('edit');
            Route::put('/update/{category}', [GameCategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{category}', [GameCategoryController::class, 'destroy'])->name('destroy');
        });

        // --- Tài khoản (đồng bộ cấu trúc) ---
        Route::prefix('accounts')->name('accounts.')->group(function () {
            // Tổng quan
            Route::get('/', [GameAccountController::class, 'index'])->name('index');
            Route::get('/create', [GameAccountController::class, 'create'])->name('create'); // Backward compat
            Route::post('/store', [GameAccountController::class, 'store'])->name('store');
            Route::get('/edit/{account}', [GameAccountController::class, 'edit'])->name('edit');
            Route::put('/update/{account}', [GameAccountController::class, 'update'])->name('update');
            Route::post('/export', [GameAccountController::class, 'exportSelected'])->name('export');
            Route::delete('/delete/{account}', [GameAccountController::class, 'destroy'])->name('delete');
            Route::delete('/delete-multiple', [GameAccountController::class, 'destroyMultiple'])->name('destroyMultiple');

            // --- Play ---
            Route::prefix('play')->name('play.')->group(function () {
                Route::get('/', [GameAccountController::class, 'indexPlay'])->name('index');
                Route::get('/create', [GameAccountController::class, 'createPlay'])->name('create');
                Route::post('/store', [GameAccountController::class, 'store'])->name('store');
                Route::get('/edit/{account}', [GameAccountController::class, 'edit'])->name('edit');
                Route::put('/update/{account}', [GameAccountController::class, 'update'])->name('update');
            });

            // --- Clone ---
            Route::prefix('clone')->name('clone.')->group(function () {
                Route::get('/', [GameAccountController::class, 'indexClone'])->name('index');
                Route::get('/create', [GameAccountController::class, 'createClone'])->name('create');
                Route::post('/store', [GameAccountController::class, 'store'])->name('store');
                Route::get('/edit/{account}', [GameAccountController::class, 'edit'])->name('edit');
                Route::put('/update/{account}', [GameAccountController::class, 'update'])->name('update');
            });

            // --- Random ---
            Route::prefix('random')->name('random.')->group(function () {
                Route::get('/', [RandomAccountController::class, 'index'])->name('index');
                Route::get('/create', [RandomAccountController::class, 'create'])->name('create');
                Route::post('/store', [RandomAccountController::class, 'store'])->name('store');
                Route::get('/edit/{id}', [RandomAccountController::class, 'edit'])->name('edit');
                Route::put('/update/{id}', [RandomAccountController::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [RandomAccountController::class, 'destroy'])->name('destroy');
            });
        });

        // --- Lịch sử ---
        Route::prefix('history')->name('history.')->group(function () {
            Route::get('/accounts', [HistoryController::class, 'accounts'])->name('accounts');
        });
    });
