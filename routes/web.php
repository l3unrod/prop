<?php

// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Admin\AdminAuthController as AdminAuth;  // ใช้ alias
use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TransferDataController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\HistoryReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DownloadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Admin Auth Routes
Route::group(['middleware' => 'guest'], function(){
    Route::get('admin/login', [AdminAuth::class, 'index'])->name('admin.login');
    Route::get('admin/forget-password', [AdminAuth::class, 'forgetPassword'])->name('admin.forget-password');
});

/** Profile Router*/
Route::group(['middleware' => 'auth', 'as' => 'profile.'], function(){
    Route::put('profile',[ProfileController::class, 'updateProfile'])->name('update');
    Route::put('profile/password',[ProfileController::class, 'updatePassword'])->name('password.update');
    Route::post('profile/avatar',[ProfileController::class, 'updateAvatar'])->name('avatar.update');
});

// Dashboard Route
Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');

/** Frontend Router */
Route::get('/', function () {
    return redirect('/main');
});

Route::get('/main', [FrontendController::class, 'index'])->name('home');
Route::put('/search', [FrontendController::class, 'searchCarLicense'])->name('search_car_license');

/** ContactUs Route */
Route::resource('/contact', ContactUsController::class);

/** Data Route */
Route::resource('/data', DataController::class);

/** Import Data Route */
Route::get('/import', [ImportController::class, 'index'])->name('import.index');
Route::get('/import/edit/{id}', [ImportController::class, 'edit'])->name('import.edit');
Route::delete('/import/destroy/{id}', [ImportController::class, 'destroy'])->name('import.destroy');
Route::post('/import/excel', [ImportController::class, 'import'])->name('import.excel');

/** Route clear data */
Route::post('/truncate-table', [ProductController::class, 'truncateTable'])->name('truncate.table');

/** FetchData GlobalHouse Router */
Route::resource('fetchData', TransferDataController::class);

/** History */
Route::resource('history', HistoryReportController::class);

/** Download File */
Route::get('downloadSampleFile', [DownloadController::class, 'downloadSampleFile'])->name('download.sample');
Route::resource('/data', DataController::class)->middleware('auth');


// ป้องกันไม่ให้ผู้ใช้เข้าถึงหน้าแดชบอร์ดหากยังไม่ได้ล็อกอิน
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware('auth');

require __DIR__.'/auth.php';
