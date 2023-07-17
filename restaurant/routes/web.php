<?php

use Illuminate\Support\Facades\Route;

/** 
 ** Home 
 */
use App\Http\Controllers\HomeController;
Route::get('/', [HomeController::class, 'index'])->name('home');


/** 
 ** Restaurants
 */
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\AuthControllers\RestaurantController as AuthRestaurantController;
Route::prefix('restaurants')->group(function () {
    //public routes
    Route::get('/', [RestaurantController::class, 'index'])->name('restaurants');
    Route::get('/show/{url}', [RestaurantController::class, 'show'])->name('restaurant.show');
    //protected routes
    Route::get('/show', [AuthRestaurantController::class, 'show'])->name('restaurants.show');
    Route::get('/create', [AuthRestaurantController::class, 'create'])->name('restaurant.create');
    Route::post('/store', [AuthRestaurantController::class, 'store'])->name('restaurant.store');
    Route::get('/edit/{id}', [AuthRestaurantController::class, 'edit'])->name('restaurant.edit');
    Route::put('/update/{id}', [AuthRestaurantController::class, 'update'])->name('restaurant.update');
    Route::delete('/delete/{id}', [AuthRestaurantController::class, 'delete']);
});

/** 
 ** Contacts 
 */
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AuthControllers\ContactController as AuthContactController;
Route::prefix('contacts')->group(function () {
    //public routes
    Route::get('/create', [ContactController::class, 'create'])->name('contact');
    Route::post('/store', [ContactController::class, 'store']);
    //protected routes
    Route::get('/show', [AuthContactController::class, 'show'])->name('contacts.show');
    Route::get('/edit/{id}', [AuthContactController::class, 'edit']);
    Route::put('/update/{id}', [AuthContactController::class, 'update']);
    Route::delete('/delete/{id}', [AuthContactController::class, 'delete']);
});

/** 
 ** Comments 
 */
use App\Http\Controllers\AuthControllers\CommentController as AuthCommentController;
Route::prefix('comments')->group(function () {
    Route::get('/show', [AuthCommentController::class, 'show'])->name('comments.show');
    Route::get('/edit/{id}', [AuthCommentController::class, 'edit']);
    Route::post('/store', [AuthCommentController::class, 'store'])->name('comment.store');
    Route::delete('/delete/{id}', [AuthCommentController::class, 'delete']);
    Route::put('/update/{id}', [AuthCommentController::class, 'update'])->name('comment.update');
});

/** 
 ** Users 
 */
use App\Http\Controllers\UserController as AuthUserController;
use App\Http\Controllers\UserController;
Route::prefix('auth')->group(function () {
    //protected routes
    Route::get('/show', [AuthUserController::class, 'show']);
    Route::get('/edit/{id}', [AuthUserController::class, 'edit']);
    Route::delete('/delete/{id}', [AuthUserController::class, 'delete']);
    Route::put('/update/{id}', [AuthUserController::class, 'update']);

});

/** 
 ** Admin 
 */
use App\Http\Controllers\AdminControllers\AdminController;
use App\Http\Controllers\AdminControllers\RestaurantController as AdminRestaurantController;
use App\Http\Controllers\AdminControllers\ContactController as AdminContactController;
use App\Http\Controllers\AdminControllers\TagController as AdminTagController;
use App\Http\Controllers\AdminControllers\FoodTypeController as AdminFoodTypeController;
use App\Http\Controllers\AdminControllers\UserController as AdminUserController;
Route::prefix('admin')->group(function (){

    Route::get('/contacts/show', [AdminContactController::class, 'show'])->name('admin.contacts.show');

    Route::get('/restaurants/show', [AdminRestaurantController::class, 'show'])->name('admin.restaurants.show');

    Route::get('/tags/show', [AdminTagController::class, 'show'])->name('admin.tags.show');

    Route::get('/foodtypes/show', [AdminFoodTypeController::class, 'show'])->name('admin.foodTypes.show');

    Route::get('/users/show', [AdminUserController::class, 'show'])->name('admin.users.show');

    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');


});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
