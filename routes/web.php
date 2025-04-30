<?php

use App\Http\Livewire\Restaurant\TotalSales;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Customer\Checkout;
use App\Http\Livewire\Customer\ViewCart;
use App\Http\Livewire\Admin\AdminDashboard;
use App\Http\Livewire\Auth\Admin\AdminLogin;
use App\Http\Livewire\Customer\CustViewMenu;
use App\Http\Livewire\Customer\CustDashboard;
use App\Http\Livewire\Auth\Customer\CustLogin;
use App\Http\Livewire\Admin\RestaurantApproval;
use App\Http\Livewire\Auth\Admin\AdminRegister;
use App\Http\Livewire\Restaurant\RestaurantMenu;
use App\Http\Livewire\Auth\Customer\CustRegister;
use App\Http\Livewire\Customer\OrderConfirmation;
use App\Http\Livewire\Restaurant\ManagerDashboard;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Livewire\Restaurant\RestaurantProfile;
use App\Http\Livewire\Auth\RestaurantManager\RestaurantLogin;
use App\Http\Livewire\Auth\RestaurantManager\RestaurantRegister;
use App\Http\Livewire\Auth\RestauantManager\RestaurantManagerLogin;
use App\Http\Livewire\Auth\RestauantManager\RestaurantManagerRegister;

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

// Route::get('/', function () {return view('welcome');});

//Customer Routes
Route::get('/register', CustRegister::class)->name('cust.register');
Route::get('/login', CustLogin::class)->name('cust.login');
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cust/home', CustDashboard::class)->name('cust.dashboard');
    Route::get('/cust/view-menu/{restaurantId}', CustViewMenu::class)->name('cust.view-menu');
    Route::get('/cart', ViewCart::class)->name('customer.cart');
    Route::get('checkout/{encryptedOrderId}', Checkout::class)->name('cust.checkout');
    Route::get('/orders/{encryptedOrderId}/confirmation', OrderConfirmation::class)
    ->name('customer.order.confirmation');
    // PayPal Routes
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/error', [PaymentController::class, 'error'])->name('error');
        Route::post('/notify', [PaymentController::class, 'notify'])->name('notify');
    });
    Route::post('/logout', function() {
        Auth::logout();
        return redirect()->route('cust.login');
    })->name('cust.logout');
});

//Restaurant Manager Routes
Route::get('/manager/register', RestaurantManagerRegister::class)->name('manager.register');
Route::get('/manager/login', RestaurantManagerLogin::class)->name('manager.login');
Route::middleware(['auth', 'role:restaurant_manager'])->group(function () {
    Route::get('/manager/dashboard', ManagerDashboard::class)->name('manager.dashboard');
    Route::get('/manager/restaurant', RestaurantProfile::class)->name('manager.restaurant-profile');
    Route::get('/manager/restaurant-menu/{restaurantId}', RestaurantMenu::class)
    ->name('manager.restaurant-menu');
    Route::get('/manager/restaurant-total-sales', TotalSales::class)->name('manager.restaurant-total-sales');
    Route::post('/manager/logout', function() {
        Auth::logout();
        return redirect()->route('manager.login');
    })->name('manager.logout');
});

//Admin Routes
Route::get('/admin/register', AdminRegister::class)->name('admin.register');
Route::get('/admin/login', AdminLogin::class)->name('admin.login');
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/admin/restaurant-approval', RestaurantApproval::class)->name('admin.restaurant-approval');
    Route::post('/admin/logout', function() {
        Auth::logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');
});