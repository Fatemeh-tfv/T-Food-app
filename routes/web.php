<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FoodItemReviewController;

Route::middleware(['auth', 'role:restaurant'])->group(function () {
    Route::get('/restaurant/dashboard',[RestaurantDashboardController::class, 'index'])->name('restaurant.dashboard');
    Route::get('/restaurant/profile/edit', [RestaurantDashboardController::class, 'editProfile'])->name('restaurant.profile.edit');
    Route::post('/restaurant/profile/update', [RestaurantDashboardController::class, 'updateProfile'])->name('restaurant.profile.update');
    Route::patch('/restaurant/orders/{order}/status', [RestaurantDashboardController::class, 'updateOrderStatus'])->name('restaurant.orders.updateStatus');
    Route::post('/restaurant/shipping-fee/update', [RestaurantDashboardController::class, 'updateShippingFee'])->name('restaurant.shipping.update');
    Route::get('/foods/create', [FoodItemController::class, 'create'])->name('foods.create');
    Route::post('/foods/store', [FoodItemController::class, 'store'])->name('foods.store');
    Route::get('/foods/{id}/edit', [FoodItemController::class, 'edit'])->name('foods.edit'); 
    Route::put('/foods/update/{id}', [FoodItemController::class, 'update'])->name('foods.update'); 
    Route::delete('/foods/{foodItem}', [FoodItemController::class, 'destroy'])->name('foods.destroy');
    Route::get('/foods/undo/{id}', [FoodItemController::class, 'undoDelete'])->name('foods.undo');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/restaurant/store',[AdminController::class,'storeRestaurant'])->name('admin.restaurants.store');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/restaurant/{id}',[RestaurantController::class,'show'])->name('restaurants.show');
Route::get('/food-item/{id}', [FoodItemController::class,'show'])->name('food-item.show');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register',[AuthController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::post('/order/add', [OrderController::class, 'addToOrder'])->name('order.add');
    Route::put('/order/update/{foodItemId}', [OrderController::class, 'update'])->name('cart.update');
    Route::delete('/order/remove/{foodItemId}', [OrderController::class, 'removeItem'])->name('order.removeItem');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
    Route::post('/order/apply-discount', [OrderController::class, 'applyDiscount'])->name('order.applyDiscount');
    Route::post('/order/finalize', [OrderController::class, 'finalizeOrder'])->name('order.finalize');
    Route::get('/order/status/{orderId}', [OrderController::class, 'showOrderStatus'])->name('order.status');
    Route::post('/reviews', [FoodItemReviewController::class, 'store'])->name('reviews.store');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/profile/show',[UserController::class,'show'])->name('user.profile.show');
});

Route::get('/about', function () {
    return view('template.about');
});

Route::get('/menu', function () {
    return view('template.menu');
});

Route::get('/contact', function () {
    return view('template.contact');
});