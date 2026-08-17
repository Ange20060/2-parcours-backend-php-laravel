<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('articles', ArticleApiController::class);

Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);

    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Identifiants invalides.'], 401);   // Fail Fast
    }
    return response()->json(['token' => $user->createToken('api')->plainTextToken]);
});

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('articles', ArticleApiController::class)->except(['index', 'show']);
});
