<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageUserController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post("login", "login");
        Route::post("register", "register");
    });
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post("logout", [AuthController::class, "logout"]);

    Route::prefix("user")->group(function () {
        Route::controller(ManageUserController::class)->group(function () {
            Route::get("profile", "me");
            Route::put("update-name", "updateName");
            Route::put("update-password", "updatePassword");
        });
    });
});
