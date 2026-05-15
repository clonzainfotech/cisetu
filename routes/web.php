<?php

use App\Http\Controllers\Admin\InquiryManagementController;
use App\Http\Controllers\Api\GuestPaymentApiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Geography\DistrictController;
use App\Http\Controllers\Geography\DistrictSearchController;
use App\Http\Controllers\Geography\StateController;
use App\Http\Controllers\PublicInquiryController;
use App\Http\Controllers\Subscriptions\ContactController;
use App\Http\Controllers\Subscriptions\VillageSubscriptionController;
use App\Http\Controllers\Teams\TeamInvitationController;
use App\Http\Controllers\Users\ManagedUserController;
use App\Http\Controllers\VillagePortalController;
use App\Http\Controllers\Villages\HomeController;
use App\Http\Controllers\Villages\ShopController;
use App\Http\Controllers\Villages\TemplatePreviewController;
use App\Http\Controllers\Villages\VillageController;
use App\Http\Controllers\Villages\VillageSearchController;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function (Request $request) {
    if ($request->user()) {
        return redirect()->route('dashboard');
    }

    if ($village = $request->get('village')) {
        return app(VillagePortalController::class)->show($request);
    }

    $plans = SubscriptionPlan::query()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();

    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
        'plans' => $plans,
    ]);
})->name('home');

Route::get('/admin', function () {
    return redirect()->route('login');
});

Route::get('/contact', [PublicInquiryController::class, 'index'])->name('contact');
Route::post('/inquiries', [PublicInquiryController::class, 'store'])->name('inquiries.store');

Route::get('api/payment', [GuestPaymentApiController::class, 'payment']);
Route::get('api/v1/details', [\App\Http\Controllers\Api\VillageDataApiController::class, 'getDetails']);
Route::get('api/v1/shops', [\App\Http\Controllers\Api\VillageDataApiController::class, 'shops']);
Route::get('api/v1/homes', [\App\Http\Controllers\Api\VillageDataApiController::class, 'homes']);

Route::get('api/villages/search', VillageSearchController::class)->middleware('auth');

Route::get('api/districts/search', DistrictSearchController::class)->name('api.districts.search');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::get('contact-us', [ContactController::class, 'index'])->name('contact-us');
    Route::post('contact-us', [ContactController::class, 'store'])->name('contact-us.store');

    // Inquiry Management
    Route::get('inquiries-list', [InquiryManagementController::class, 'index'])->name('inquiries.index');
    Route::post('inquiries-list/{inquiry}/email', [InquiryManagementController::class, 'sendEmail'])->name('inquiries.email');
    Route::patch('inquiries-list/{inquiry}', [InquiryManagementController::class, 'update'])->name('inquiries.update');
    Route::delete('inquiries-list/{inquiry}', [InquiryManagementController::class, 'destroy'])->name('inquiries.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', [ManagedUserController::class, 'index'])->name('managed-users.index');
    Route::post('users', [ManagedUserController::class, 'store'])->name('managed-users.store');

    Route::get('villages', [VillageController::class, 'index'])->name('villages.index');
    Route::get('villages/create', [VillageController::class, 'create'])->name('villages.create');
    Route::post('villages', [VillageController::class, 'store'])->name('villages.store');
    Route::get('villages/{village}/edit', [VillageController::class, 'edit'])->name('villages.edit');
    Route::post('villages/{village}', [VillageController::class, 'update'])->name('villages.update');
    Route::delete('villages/{village}', [VillageController::class, 'destroy'])->name('villages.destroy');
    Route::post('villages/{village}/regenerate-token', [VillageController::class, 'regenerateToken'])->name('villages.regenerate-token');
    Route::get('template-preview/{template}', [TemplatePreviewController::class, 'show'])->name('template.preview');

    Route::get('states', [StateController::class, 'index'])->name('states.index');
    Route::post('states', [StateController::class, 'store'])->name('states.store');

    Route::get('districts', [DistrictController::class, 'index'])->name('districts.index');
    Route::post('districts', [DistrictController::class, 'store'])->name('districts.store');

    Route::get('subscriptions', [VillageSubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/plans', [VillageSubscriptionController::class, 'plans'])->name('subscriptions.plans');
    Route::get('subscriptions/{villageToken}', [VillageSubscriptionController::class, 'edit'])->name('subscriptions.edit');
    Route::put('subscriptions/{villageToken}', [VillageSubscriptionController::class, 'update'])->name('subscriptions.update');

    // Homes & Shops (Taxes)
    Route::get('homes/export', [HomeController::class, 'export'])->name('homes.export');
    Route::get('homes/template', [HomeController::class, 'template'])->name('homes.template');
    Route::post('homes/import', [HomeController::class, 'import'])->name('homes.import');
    Route::resource('homes', HomeController::class);

    Route::get('shops/export', [ShopController::class, 'export'])->name('shops.export');
    Route::get('shops/template', [ShopController::class, 'template'])->name('shops.template');
    Route::post('shops/import', [ShopController::class, 'import'])->name('shops.import');
    Route::resource('shops', ShopController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
});

require __DIR__.'/settings.php';
