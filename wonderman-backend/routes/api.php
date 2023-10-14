<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("auth/login", [AuthController::class, "login"]);

//Route::get("/products", [ProductController::class, "index"]);
Route::get("/categories", [CategoryController::class, "index"]);
Route::get("/category/{category}", [ProductController::class, "getProductsByCategory"]);
Route::get("/products/bests", [ProductController::class, "getBestProducts"]);
Route::get("/products/{id}", [ProductController::class, "show"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/auth/user", [AuthController::class, "user"]);
    Route::post("/auth/admin", [UserController::class, "createAdmin"]);
    Route::delete("/auth/logout", [AuthController::class, "logout"]);
    Route::put("/auth/password", [UserController::class, "changePassword"]);
    Route::put("/auth/profile", [UserController::class, "changeData"]);
    Route::post("/auth/avatar/change", [UserController::class, "changeAvatar"]);
    Route::put("/auth/avatar/remove", [UserController::class, "removeAvatar"]);

    Route::post("/products", [ProductController::class, "store"]);
    Route::put("/products/{id}", [ProductController::class, "update"]);
    Route::delete("/products/{id}", [ProductController::class, "destroy"]);

    Route::get("/user/transactions", [TransactionController::class, "getTransactionsForUser"]);
    Route::post("/products/{id}", [TransactionController::class, "store"]);
    Route::delete("/transactions/{id}", [TransactionController::class, "destroy"]);
    Route::get("/transactions/{id}", [TransactionController::class, "show"]);
    Route::post("/transactions/{id}", [TransactionController::class, "pay"]);
});

