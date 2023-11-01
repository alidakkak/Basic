<?php

namespace App\Repository;

use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Recipient;
use App\Models\User;
use App\Traits\AttachFilesTrait;
use App\Events\MessageCreated;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{
    use GeneralTrait,AttachFilesTrait;


public function create_message(Request $request,Conversation $conversation){
    try {
        $sender = Auth::user();
        $type=$request->post('type_message');
        // add message for this conversation and sender
        if($type=="attachment"){
            $file=  $request->file("attachment");
            $path="assets/chat_attachment";
            $name=$file->getClientOriginalName();
            $body_message = $this->uploade_image($name,$path,$file);
        }
        elseif ($type=="text"){
            $body_message=$request->post('message');
        }

        $message = $conversation
            ->messages()
            ->create([
                'user_id' => $sender->id,
                'type' => $type,
                'body' => $body_message,
            ]);


return $message;
    }
    catch (\Exception $e) {
        DB::rollBack();
        return['error' => $e->getMessage()];
    }
}
    public function show($request)
    {
        $user=Auth::user();
        $conversation_id = $request->conversation_id;
        $conversation = Conversation::findOrFail($conversation_id);
        $hidemessage= $user->hidemessage()->pluck("message_id")->toarray();
        $messages = $conversation->messages()->whereNotIn("id",$hidemessage)->orderByDesc("id")->get();
        $messages= $messages->groupBy(function ($message) {
            return $message->created_at->diffForHumans();
        });
        $transformer_message=[];
        $count_messages=0;
        foreach ($messages as $index=>$message){
            $count_messages=  $count_messages +$message->count();
            $transformer_message[$index]=MessageResource::collection($message);
        }
        $keys=["count_messages","messages"];
        $values=[$count_messages,$transformer_message];
//        return response()->json(['message' => Debugbar::getData()["memory"]]);
        return  $this->returnData(200,$keys,$values);

    }
    public function NumberOfUnreadMessage()
    {

        $user = Auth::user();
        $unread_message = $user->unreadmessage();
        $keys=["Number_Of_Unread_Messages"];
        $values=[$unread_message];
        return  $this->returnData(200,$keys,$values);
    }


    public function markAsRead($request)
    {
        $conversation_id = $request->conversation_id;
        $message_ids = Conversation::find($conversation_id)->messages()->pluck('id')->toArray();
        Recipient::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->whereIn("message_id", $message_ids)
            ->update([
                'read_at' => Carbon::now(),
            ]);
        $keys=["message"];
        $values=['Messages marked as read'];
        return  $this->returnData(200,$keys,$values);}

}
