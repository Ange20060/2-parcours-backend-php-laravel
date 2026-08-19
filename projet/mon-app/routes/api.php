<?php

use App\Http\Controllers\Api\ArticleApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Identifiants invalides.',
        ], 401);
    }

    return response()->json([
        'token' => $user->createToken('api')->plainTextToken,
    ]);
});

Route::apiResource('articles', ArticleApiController::class)
    ->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('articles', ArticleApiController::class)
        ->except(['index', 'show']);

});
