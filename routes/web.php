<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\FilbizInquiryController;
use App\Http\Controllers\FilbizUpgradeController;
use App\Http\Controllers\ResidentialInquiryController;
use App\Http\Controllers\ResidentialUpgradeController;
/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/news', [PageController::class, 'news'])->name('news');
Route::get('/complaint', [PageController::class, 'complaint'])->name('complaint');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/track', [PageController::class, 'track'])->name('track');
Route::get('/branches', [PageController::class, 'branch'])->name('branch');

/*
|--------------------------------------------------------------------------
| RESIDENTIAL
|--------------------------------------------------------------------------
*/

Route::get('/residential-inquiry', [ResidentialInquiryController::class, 'inquiry'])->name('residential.inquiry');
Route::post('/residential-inquiry', [ResidentialInquiryController::class, 'submit'])->name('residential.inquiry.submit');

Route::get('/residential-upgrade', [ResidentialUpgradeController::class, 'upgrade'])->name('residential.upgrade');
Route::post('/residential-upgrade', [ResidentialUpgradeController::class, 'submit'])->name('residential.upgrade.submit');

/*
|--------------------------------------------------------------------------
| FILBIZ
|--------------------------------------------------------------------------
*/

Route::get('/filbiz-inquiry', [FilbizInquiryController::class, 'inquiry'])->name('filbiz.inquiry');
Route::post('/filbiz-inquiry', [FilbizInquiryController::class, 'submit'])->name('filbiz.submit');

Route::get('/filbiz-upgrade', [FilbizUpgradeController::class, 'upgrade'])->name('filbiz.upgrade');
Route::post('/filbiz-upgrade', [FilbizUpgradeController::class, 'submit'])->name('filbiz.upgrade.submit');

/*
|--------------------------------------------------------------------------
| COMPLAINT
|--------------------------------------------------------------------------
*/

Route::post('/complaint-submit', [ComplaintController::class, 'submit'])->name('complaint.submit');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

Route::get('/admin/complaints', [AdminController::class, 'complaints'])->name('admin.complaints');
Route::post('/admin/complaint/status/{id}', [AdminController::class, 'updateStatus'])->name('admin.complaint.status');

Route::get('/admin/applications', [AdminController::class, 'applications'])->name('admin.applications');

Route::get('/admin/applications/residential', [AdminController::class, 'residential'])->name('admin.applications.residential');
Route::get('/download-residential/{id}', [AdminController::class, 'downloadResidential'])->name('download.residential');

Route::get('/admin/applications/filbiz', [AdminController::class, 'filbiz'])->name('admin.applications.filbiz');
Route::get('/admin/download/filbiz/{id}', [AdminController::class, 'downloadFilbiz'])->name('download.filbiz');

Route::get('/admin/transmittal-pdf', [AdminController::class, 'transmittalArea'])->name('admin.transmittal.pdf');
Route::post('/admin/transmittal-generate', [AdminController::class, 'generateTransmittal'])->name('admin.transmittal.generate');

Route::get('/admin/areas', [AdminController::class, 'areas'])->name('admin.areas');
Route::post('/admin/areas/save', [AdminController::class, 'saveArea'])->name('admin.area.save');
Route::post('/admin/areas/update/{id}', [AdminController::class, 'updateArea'])->name('admin.area.update');
Route::get('/admin/areas/delete/{id}', [AdminController::class, 'deleteArea'])->name('admin.area.delete');