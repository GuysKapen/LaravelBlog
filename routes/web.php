<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post("/subscriber", [SubscriberController::class, 'store'])->name('subscriber.store');

Route::get('/category/{slug}', [CategoryController::class, 'index'])->name('category.posts');

Route::get('/author/profile', function () {

})->name('author.profile');

Route::get('/post/details', function () {

})->name('post.details');

Route::group(["middleware" => ["auth"]], function () {
    Route::post("/favorite/{post}/add", [FavoriteController::class, 'add'])->name("post.favorite");
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('tag', 'TagController');
    Route::resource('category', 'CategoryController');
    Route::resource('post', 'PostController');

    Route::get("pending/post", 'PostController@pending')->name('post.pending');
    Route::put("post/{id}/approve", 'PostController@approve')->name('post.approve');

    Route::get("/subscriber", 'SubscriberController@index')->name('subscriber.index');
    Route::post("/subscriber/{subscriber}", 'SubscriberController@destroy')->name('subscriber.destroy');

    Route::get("settings", "SettingsController@index")->name("settings");
    Route::put("profile-update", "SettingsController@updateProfile")->name("profile.update");
    Route::put("password-update", "SettingsController@updatePassword")->name("password.update");

    Route::get("/favorite", "FavoriteController@index")->name("favorite.index");

});

Route::group(['as' => 'author.', 'prefix' => 'author', 'namespace' => 'App\Http\Controllers\Author', 'middleware' => ['auth', 'author']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('post', 'PostController');

    Route::get("settings", "SettingsController@index")->name("settings");
    Route::put("profile-update", "SettingsController@updateProfile")->name("profile.update");
    Route::put("password-update", "SettingsController@updatePassword")->name("password.update");

    Route::get("/favorite", "FavoriteController@index")->name("favorite.index");
});
