<?php
use Illuminate\Support\Facades\Route;

// ─── Society Management Routes ───────────────────────────────────────────────
use App\Http\Controllers\Society\AuthController;
use App\Http\Controllers\Society\SuperAdminController;
use App\Http\Controllers\Society\ContactController;

// Public: Landing, Auth, Contact
Route::get('/',             [AuthController::class, 'landing'])->name('society.landing');
Route::get('/login',        [AuthController::class, 'showLogin'])->name('society.login');
Route::post('/login',       [AuthController::class, 'login'])->name('society.login.post');
Route::get('/register',     [AuthController::class, 'showRegister'])->name('society.register');
Route::post('/register',    [AuthController::class, 'register'])->name('society.register.post');
Route::post('/logout',      [AuthController::class, 'logout'])->name('society.logout');
Route::post('/contact',     [ContactController::class, 'send'])->name('society.contact.send');
Route::get('/api/societies/{city}', [AuthController::class, 'getSocietiesByCity'])->name('api.societies.by-city');
Route::get('/api/society/{society}/buildings', [AuthController::class, 'getBuildings'])->name('api.buildings');
Route::get('/api/building/{building}/floors',   [AuthController::class, 'getFloors'])->name('api.floors');
Route::get('/api/building/{building}/units',    [AuthController::class, 'getUnits'])->name('api.units');

// User Panel (role:3)
use App\Http\Controllers\Society\SocietyUserController;
Route::middleware(['auth', 'role:3', 'society_approved', 'society_active'])->prefix('user')->name('society.user.')->group(function () {
    Route::get('/dashboard',       [SocietyUserController::class, 'dashboard'])->name('dashboard');
    Route::get('/passbook',        [SocietyUserController::class, 'passbook'])->name('passbook');
    Route::get('/settings',        [SocietyUserController::class, 'settings'])->name('settings');
    Route::post('/profile/update',  [SocietyUserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password/update', [SocietyUserController::class, 'updatePassword'])->name('password.update');
});

// Society Admin Dashboard (role:2)
use App\Http\Controllers\Society\SocietyAdminController;

Route::middleware(['auth', 'role:2', 'society_active'])->prefix('society-admin')->name('society-admin.')->group(function () {
    Route::get('/dashboard',              [SocietyAdminController::class, 'dashboard'])->name('dashboard');
    
    // Structure
    Route::get('/structure',              [SocietyAdminController::class, 'structure'])->name('structure');
    Route::post('/structure/building',    [SocietyAdminController::class, 'storeBuilding'])->name('structure.building.store');
    Route::post('/structure/units',       [SocietyAdminController::class, 'storeUnits'])->name('structure.units.store');

    // Users
    Route::get('/users',                  [SocietyAdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/approve', [SocietyAdminController::class, 'approveUser'])->name('users.approve');
    Route::delete('/users/{user}/reject', [SocietyAdminController::class, 'rejectUser'])->name('users.reject');

    // Maintenance
    Route::get('/maintenance',            [SocietyAdminController::class, 'maintenance'])->name('maintenance');
    Route::post('/maintenance/bill',      [SocietyAdminController::class, 'storeBill'])->name('maintenance.bill.store');

    // Passbook
    Route::get('/passbook',               [SocietyAdminController::class, 'passbook'])->name('passbook');
    Route::post('/passbook/entry',        [SocietyAdminController::class, 'storePassbookEntry'])->name('passbook.entry.store');

    // Settings
    Route::get('/settings',               [SocietyAdminController::class, 'settings'])->name('settings');
    Route::post('/settings/profile',      [SocietyAdminController::class, 'updateProfile'])->name('settings.profile');
});

// Super Admin Routes (role:1)
Route::middleware(['auth', 'role:1'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard',                       [SuperAdminController::class, 'dashboard'])->name('dashboard');

    // Societies
    Route::get('/societies',                       [SuperAdminController::class, 'societies'])->name('societies');
    Route::get('/societies/create',                [SuperAdminController::class, 'createSociety'])->name('societies.create');
    Route::post('/societies',                      [SuperAdminController::class, 'storeSociety'])->name('societies.store');
    Route::get('/societies/{society}/edit',        [SuperAdminController::class, 'editSociety'])->name('societies.edit');
    Route::put('/societies/{society}',             [SuperAdminController::class, 'updateSociety'])->name('societies.update');
    Route::patch('/societies/{society}/toggle',    [SuperAdminController::class, 'toggleSociety'])->name('societies.toggle');
    Route::delete('/societies/{society}',          [SuperAdminController::class, 'deleteSociety'])->name('societies.delete');
    Route::post('/societies/{society}/admin',      [SuperAdminController::class, 'createSocietyAdmin'])->name('societies.admin.create');

    // Plans
    Route::get('/plans',                           [SuperAdminController::class, 'plans'])->name('plans');
    Route::get('/plans/create',                    [SuperAdminController::class, 'createPlan'])->name('plans.create');
    Route::post('/plans',                          [SuperAdminController::class, 'storePlan'])->name('plans.store');
    Route::get('/plans/{plan}/edit',               [SuperAdminController::class, 'editPlan'])->name('plans.edit');
    Route::put('/plans/{plan}',                    [SuperAdminController::class, 'updatePlan'])->name('plans.update');
    Route::patch('/plans/{plan}/toggle',           [SuperAdminController::class, 'togglePlan'])->name('plans.toggle');
    Route::delete('/plans/{plan}',                 [SuperAdminController::class, 'deletePlan'])->name('plans.delete');

    // Users
    Route::get('/users',                           [SuperAdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle',           [SuperAdminController::class, 'toggleUser'])->name('users.toggle');

    // Settings
    Route::get('/settings',                        [SuperAdminController::class, 'settings'])->name('settings');
    Route::post('/settings/profile',               [SuperAdminController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password',              [SuperAdminController::class, 'updatePassword'])->name('settings.password');
});
