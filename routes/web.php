<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;;

Route::get('/', [PageController::class, 'home'])->name('home');

Route::get('/news', [PageController::class, 'news'])->name('news');
Route::get('/complaint', [PageController::class, 'complaint'])->name('complaint');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/residential-inquiry', [PageController::class, 'residentialInquiry'])->name('residential.inquiry');
Route::get('/residential-upgrade', [PageController::class, 'residentialUpgrade'])->name('residential.upgrade');

Route::get('/filbiz-inquiry', [PageController::class, 'filbizInquiry'])->name('filbiz.inquiry');
Route::get('/filbiz-upgrade', [PageController::class, 'filbizUpgrade'])->name('filbiz.upgrade');
Route::post('/complaint-submit', [PageController::class,'submitComplaint'])->name('complaint.submit');
Route::post('/filbiz-inquiry', [PageController::class,'submitFilbiz'])->name('filbiz.submit');
Route::post('/filbiz-upgrade', [PageController::class, 'submitFilbizUpgrade'])->name('filbiz.upgrade.submit');
Route::post('/residential-inquiry', [PageController::class, 'submitResidential'])->name('residential.inquiry.submit');
Route::post('/residential-upgrade', [PageController::class, 'submitResidentialUpgrade'])->name('residential.upgrade.submit');

Route::get('/admin',[AdminController::class,'dashboard'])->name('admin.dashboard');

Route::get('/admin/complaints',[AdminController::class,'complaints'])->name('admin.complaints');

Route::get('/admin/applications',[AdminController::class,'applications'])->name('admin.applications');
Route::post('/admin/complaint/status/{id}', [AdminController::class, 'updateStatus'])->name('admin.complaint.status');
Route::get('/admin/transmittal-pdf', [AdminController::class,'transmittalArea'])->name('admin.transmittal.pdf');
Route::post('/admin/transmittal-generate',[AdminController::class,'generateTransmittal'])->name('admin.transmittal.generate');

Route::get('/admin/areas', [AdminController::class,'areas'])->name('admin.areas');
Route::post('/admin/areas/save', [AdminController::class,'saveArea'])->name('admin.area.save');
Route::post('/admin/areas/update/{id}', [AdminController::class,'updateArea'])->name('admin.area.update');
Route::get('/admin/areas/delete/{id}', [AdminController::class,'deleteArea'])->name('admin.area.delete');

Route::get('/admin/applications/residential', [AdminController::class, 'residential'])->name('admin.applications.residential');
Route::get('/download-residential/{id}',[AdminController::class,'downloadResidential'])->name('download.residential');
Route::get('/admin/applications/filbiz', [AdminController::class, 'filbiz'])->name('admin.applications.filbiz');
Route::get('/admin/download/filbiz/{id}', [AdminController::class, 'downloadFilbiz'])->name('download.filbiz');
Route::get('/track',[PageController::class,'track'])->name('track');
Route::get('/branches', [PageController::class, 'branch'])->name('branch');