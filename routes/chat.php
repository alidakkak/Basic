<?php

use App\Http\Controllers\chat\ConversationController;
use App\Http\Controllers\chat\GroupController;
use App\Http\Controllers\chat\MessageController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(["middleware"=>'jwt.auth'],function (){
    // conversations
    Route::get("/IndexConversation",[ConversationController::class,'index']);
    Route::get("/ShowConversation",[ConversationController::class,'show']);
    Route::get("/NumberOfUnreadMessage",[ConversationController::class,'NumberOfUnreadMessage']);
    Route::put("/markAsRead",[ConversationController::class,'markAsRead']);
    Route::get("/getconversation",[ConversationController::class,'getconversation']);
    // messages
    Route::post("/CreateMessage",[MessageController::class,'store']);
    //group
    Route::post("/make_group",[GroupController::class,'make_group']);
    Route::post("/add_members_for_group",[GroupController::class,'add_member_after_make_group']);

});



