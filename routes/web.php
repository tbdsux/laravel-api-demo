<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["success" => true, "message" => "Laravel API Demo (v12.x)"]);
});
