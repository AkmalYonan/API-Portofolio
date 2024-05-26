<?php

use App\Http\Controllers\SkillController;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('skill', SkillController::class);

// Route::fallback(function () {
//     return response()->json(['message' => 'Sorry, Data not Found'], 404);
// });

Route::delete('skill/bulk-delete/{start}/{end}', [SkillController::class, 'bulkDelete']);

Route::any('{any}', function () {
    return response()->json([
        'status'    => false,
        'message'   => 'Page Not Found.',
    ], 404);
})->where('any', '.*');
