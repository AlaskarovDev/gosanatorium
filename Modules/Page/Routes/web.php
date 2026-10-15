<?php

use Illuminate\Support\Facades\Route;
use Modules\Page\App\Http\Controllers\PageController;
use Modules\Page\App\Http\Controllers\PrivacyController;
use Modules\Page\App\Http\Controllers\CustomPageController;
use Modules\Page\App\Http\Controllers\ConsultationController;
use Modules\Page\App\Http\Controllers\TermsConditiondController;
use Modules\Page\App\Http\Controllers\ContactPageController;
use Modules\Page\App\Http\Controllers\FooterContrllerController;

// Admin Routes
Route::group(['as'=> 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:admin']], function () {
    Route::get('terms-conditions', [TermsConditiondController::class, 'index'])->name('terms-conditions');
    Route::put('update-terms-conditions', [TermsConditiondController::class, 'update'])->name('update-terms-conditions');

    Route::get('privacy-policy', [PrivacyController::class, 'index'])->name('privacy-policy');
    Route::put('update-privacy-policy', [PrivacyController::class, 'update'])->name('update-privacy-policy');

    Route::get('contact-us', [ContactPageController::class, 'index'])->name('contact-us');
    Route::put('update-contact-us', [ContactPageController::class, 'update'])->name('update-contact-us');
    
Route::post('custom-page-videos', [CustomPageController::class, 'storeVideo']);
Route::delete('custom-page-videos/{id}', [CustomPageController::class, 'destroyVideo']);
Route::patch('custom-page-videos/{id}/status', [CustomPageController::class, 'updateVideoStatus']);
    
    
    
    Route::get('footer', [FooterContrllerController::class, 'index'])->name('footer');
    Route::put('update-footer', [FooterContrllerController::class, 'update'])->name('update-footer');

    Route::resource('custom-page', CustomPageController::class);

    // Consultation requests management
    Route::get('consultation-requests', [ConsultationController::class, 'index'])->name('consultation-requests.index');
    Route::get('consultation-requests/{id}', [ConsultationController::class, 'show'])->name('consultation-requests.show');
    Route::delete('consultation-requests/{id}', [ConsultationController::class, 'destroy'])->name('consultation-requests.destroy');
});

// Frontend Route
Route::post('consultation/store', [ConsultationController::class, 'store'])->name('consultation.store');