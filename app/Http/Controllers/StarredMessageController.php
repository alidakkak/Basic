<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\ConversationResource;
use App\Models\StarredMessage;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class StarredMessageController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $star = StarredMessage::where('user_id', auth()->user()->id)->get();
        $keys=["message","starred messages"];
        $values=['Added SuccessFully',$star];
        return  $this->returnData(200,$keys,$values);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageRequest $request)
    {
        $user = auth()->user();
        $starred = StarredMessage::where(
            ['user_id' => $user->id],
            ['message_id'=> $request->message_id]
        )->first();
        if ($starred){
            $keys=["message","starred"];
            $values=['Added SuccessFully',$starred];
            return  $this->returnData(200,$keys,$values);
        }
        $starred = StarredMessage::create([
            'user_id' => $user->id,
            'message_id' => $request->message_id
        ]);
        $keys=["message","starred"];
        $values=['Added SuccessFully',$starred];
        return  $this->returnData(200,$keys,$values);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $star)
    {
        $starred = StarredMessage::find($star);
        if (!$starred){
            return response()->json(['error' =>' star message not found']);
        }
            $star->delete();
            $keys = ["message"];
            $values = ['Deleted SuccessFully'];
            return $this->returnData(204, $keys, $values);

    }

}
