<?php

use App\Http\Controllers\WorkbenchController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('workbench.dashboard'));

Route::controller(WorkbenchController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('workbench.dashboard');
    Route::get('/services',  'services')->name('workbench.services');
    Route::get('/customers', 'customers')->name('workbench.customers');
    Route::get('/emails',    'emails')->name('workbench.emails');
    Route::get('/tracking',  'tracking')->name('workbench.tracking');
    Route::get('/insights',  'insights')->name('workbench.insights');
    Route::get('/support',   'support')->name('workbench.support');
});
