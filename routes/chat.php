<?php

use App\Http\Controllers\chat\ConversationController;
use App\Http\Controllers\chat\FeatureController;
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
    Route::get("/ArchivedConversation",[ConversationController::class,'archived']);

    Route::get("/ShowConversation",[ConversationController::class,'show']);
    Route::get("/NumberOfUnreadMessage",[ConversationController::class,'NumberOfUnreadMessage']);
    Route::put("/markAsRead",[ConversationController::class,'markAsRead']);
    Route::get("/getconversation",[ConversationController::class,'getconversation']);
    // messages
    Route::post("/CreateMessage",[MessageController::class,'store']);
    Route::delete("/deleteMessageForAll",[MessageController::class,'delete']);
    Route::delete("/deleteMessageForMe",[MessageController::class,'delete_for_me']);


    //group
    Route::post("/make_group",[GroupController::class,'make_group']);
    Route::group(["middleware"=>'check_membership:admin,member'],function (){
        Route::delete("/exit_from_group",[GroupController::class,'exit_from_group']);
        Route::get("/fetch_information_conversation",[ConversationController::class,'fetch_information_conversation']);
    });
    Route::group(["middleware"=>'check_membership:admin'],function (){
        Route::post("/add_members_for_group",[GroupController::class,'add_member_after_make_group']);
        Route::delete("/delete_member_for_group",[GroupController::class,'delete_member_of_group']);
    });
    Route::put("/pinned_and_unpinned",[FeatureController::class,'pinned_unpinned']);
    Route::put("/archived_and_unarchived",[FeatureController::class,'archived_unarchived']);
    Route::put("/muted_and_unmute",[FeatureController::class,'muted_unmute']);
    Route::put("/blocked_and_unblocked",[FeatureController::class,'block_unblock']);


});



