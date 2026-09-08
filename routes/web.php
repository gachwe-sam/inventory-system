<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchManagerStaffController;
use App\Http\Controllers\BranchStockController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // TEMPORARILY OPEN to any logged-in user — no role gate right now.
    // To restore restrictions later: wrap create/store/edit/update/destroy
    // (not index/show) in Route::middleware('role:admin')->group(...).
    Route::get('/categories/export/pdf', [CategoryController::class, 'exportPdf'])->name('categories.export.pdf');
    Route::get('/categories/export/{format}', [CategoryController::class, 'exportExcel'])->whereIn('format', ['xlsx', 'csv'])->name('categories.export');
    Route::post('/categories/import', [CategoryController::class, 'import'])->name('categories.import');
    Route::post('/categories/import/undo', [CategoryController::class, 'undoImport'])->name('categories.import.undo');
    Route::get('/categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::resource('categories', CategoryController::class);

    Route::get('/items/export/pdf', [ItemController::class, 'exportPdf'])->name('items.export.pdf');
    Route::get('/items/export/{format}', [ItemController::class, 'exportExcel'])->whereIn('format', ['xlsx', 'csv'])->name('items.export');
    Route::post('/items/import', [ItemController::class, 'import'])->name('items.import');
    Route::get('/items/import/template', [ItemController::class, 'downloadImportTemplate'])->name('items.import.template');
    Route::post('/items/import/undo', [ItemController::class, 'undoImport'])->name('items.import.undo');
    Route::get('/items/data', [ItemController::class, 'data'])->name('items.data');
    Route::resource('items', ItemController::class);

    Route::get('/branches/data', [BranchController::class, 'data'])->name('branches.data');
    Route::resource('branches', BranchController::class);
    Route::get('/suppliers/data', [SupplierController::class, 'data'])->name('suppliers.data');
    Route::resource('suppliers', SupplierController::class);
    Route::get('/purchases/data', [PurchasesController::class, 'data'])->name('purchases.data');
    Route::resource('purchases', PurchasesController::class);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Left as-is on purpose: this is branch DATA-scoping via
    // BranchstockPolicy, not a role/page lock, so it's a different
    // concern from the "open everything up" change above.
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/data', [StockController::class, 'data'])->name('stock.data');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('/branch-stock/{branchStock}/edit', [BranchStockController::class, 'edit'])->name('branch-stock.edit');
    Route::patch('/branch-stock/{branchStock}', [BranchStockController::class, 'update'])->name('branch-stock.update');
    Route::get('/branch-stock/{branchStock}/receive', [BranchStockController::class, 'receiveForm'])->name('branch-stock.receive.form');
    Route::post('/branch-stock/{branchStock}/receive', [BranchStockController::class, 'receive'])->name('branch-stock.receive');
    Route::get('/branch-stock/{branchStock}/issue', [BranchStockController::class, 'issueForm'])->name('branch-stock.issue.form');
    Route::post('/branch-stock/{branchStock}/issue', [BranchStockController::class, 'issue'])->name('branch-stock.issue');
    Route::get('/branch-stock/{branchStock}/transfer', [BranchStockController::class, 'transferForm'])->name('branch-stock.transfer.form');
    Route::post('/branch-stock/{branchStock}/transfer', [BranchStockController::class, 'transfer'])->name('branch-stock.transfer');
    Route::get('/branch-stock/{branchStock}/history', [BranchStockController::class, 'history'])->name('branch-stock.history');

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/staff', [BranchManagerStaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/data', [BranchManagerStaffController::class, 'data'])->name('staff.data');
        Route::get('/staff/{user}/edit', [BranchManagerStaffController::class, 'edit'])->name('staff.edit');
        Route::patch('/staff/{user}', [BranchManagerStaffController::class, 'update'])->name('staff.update');
    });
});

require __DIR__.'/auth.php';