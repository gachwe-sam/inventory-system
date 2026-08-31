<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchManagerStaffController;
use App\Http\Controllers\BranchStockController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/categories/export/pdf', [CategoryController::class, 'exportPdf'])->name('categories.export.pdf');
    Route::get('/categories/export/{format}', [CategoryController::class, 'exportExcel'])
        ->whereIn('format', ['xlsx', 'csv'])
        ->name('categories.export');
    Route::post('/categories/import', [CategoryController::class, 'import'])->name('categories.import');
    Route::post('/categories/import/undo', [CategoryController::class, 'undoImport'])->name('categories.import.undo');
    Route::resource('categories', CategoryController::class);
    Route::get('/items/export/pdf', [ItemController::class, 'exportPdf'])->name('items.export.pdf');
    Route::get('/items/export/{format}', [ItemController::class, 'exportExcel'])
        ->whereIn('format', ['xlsx', 'csv'])
        ->name('items.export');
    Route::post('/items/import', [ItemController::class, 'import'])->name('items.import');
    Route::post('/items/import/undo', [ItemController::class, 'undoImport'])->name('items.import.undo');
    Route::resource('items', ItemController::class);   
    Route::resource('suppliers', SupplierController::class);
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   Route::post('/quick-logout', [UserController::class, 'logout'])->name('user.logout');

Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::post('/stock', [StockController::class, 'store'])
    ->middleware('auth')
    ->name('stock.store');


Route::get('/branch-stock/{branchStock}/edit', [BranchStockController::class, 'edit'])->name('branch-stock.edit');
Route::patch('/branch-stock/{branchStock}', [BranchStockController::class, 'update'])->name('branch-stock.update');
Route::get('/branch-stock/{branchStock}/receive', [BranchStockController::class, 'receiveForm'])->name('branch-stock.receive.form');
Route::post('/branch-stock/{branchStock}/receive', [BranchStockController::class, 'receive'])->name('branch-stock.receive');
Route::get('/branch-stock/{branchStock}/issue', [BranchStockController::class, 'issueForm'])->name('branch-stock.issue.form');
Route::post('/branch-stock/{branchStock}/issue', [BranchStockController::class, 'issue'])->name('branch-stock.issue');
Route::get('/branch-stock/{branchStock}/transfer', [BranchStockController::class, 'transferForm'])->name('branch-stock.transfer.form');
Route::post('/branch-stock/{branchStock}/transfer', [BranchStockController::class, 'transfer'])->name('branch-stock.transfer');
Route::get('/branch-stock/{branchStock}/history', [BranchStockController::class, 'history'])->name('branch-stock.history');

Route::middleware('role:admin')->group(function () {
    Route::resource('branches', BranchController::class);
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::resource('suppliers', SupplierController::class);
});

Route::middleware('role:branch_manager')->prefix('manager')->name('manager.')->group(function () {
    Route::get('/staff', [BranchManagerStaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/{user}/edit', [BranchManagerStaffController::class, 'edit'])->name('staff.edit');
    Route::patch('/staff/{user}', [BranchManagerStaffController::class, 'update'])->name('staff.update');
});

});


Route::middleware('guest')->group(function () {
    Route::get('otp/request', [OtpController::class, 'showRequestForm'])->name('otp.request');
    Route::get('otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');

    Route::get('/quick-register', [UserController::class, 'showRegistrationForm'])->name('user.register.form');
    Route::post('/quick-register', [UserController::class, 'register'])->name('user.register');
    Route::get('/quick-login', [UserController::class, 'showLoginForm'])->name('user.login.form');
    Route::post('/quick-login', [UserController::class, 'login'])->name('user.login');
});

require __DIR__.'/auth.php';