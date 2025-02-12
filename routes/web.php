<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

Route::get("/", function () {
    throw new NotFoundHttpException();
});

Route::get("/login", function () {
    return response()->json(["message" => "unauthenticated"], 401);
})->name("login");
