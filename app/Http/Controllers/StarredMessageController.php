<?php

namespace App\Http\Controllers;

use App\Models\StarredMessage;
use Illuminate\Http\Request;

class StarredMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $star = StarredMessage::where('user_id', auth()->user()->id)->get();
        return $star;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $star = StarredMessage::create([
            'user_id' => $user->id,
            'message_id' => $request->message_id
        ]);
        return response()->json('Added SuccessFully');
    }

    /**
     * Display the specified resource.
     */
    public function show(StarredMessage $starredMessage)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StarredMessage $starredMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StarredMessage $star)
    {
        $star->delete();
        return response()->json([
            "Starred Message Deleted SuccessFully",
            $star
        ]);
    }
}
