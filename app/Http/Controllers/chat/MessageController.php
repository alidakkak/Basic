<?php

namespace App\Http\Controllers\chat;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Recipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $sender = Auth::user();
        $receiver = $request->post('receiver_id');
        $type=$request->post('type_message');
        $body_message = $request->post('message');
        DB::beginTransaction();
        try {
            //find if there is old conversation
          $conversation = $sender
              ->conversations()
              ->where("type","peer")
              ->whereHas('members',function ($builder) use ($receiver){
              $builder->where('user_id',$receiver);
                })
              ->first();

          //check if  there is no conversation => create new conversation

            if (!$conversation) {
                $conversation = Conversation::create([
                    "user_id" => $sender->id,
                    "type" => "peer",
                ]);

                // add members for this conversation
                $conversation->members()->attach([
                    $sender->id => ['joined_at' => now()],
                    $receiver => ['joined_at' => now()],
                ]);
            }
            // add message for this conversation and sender
            if($type=="attachment"){
                $file=  $request->file("attachment");
                $path="assets/chat_attachment";
                $name=$file->getClientOriginalName();
                $body_message = uploade_image($name,$path,$file);
            }
            $message = $conversation
                    ->messages()
                    ->create([
                    'user_id' => $sender->id,
                    'type' => $type,
                    'body' => $body_message,
                    ]);

            // add recipient for this message and receiver
         $recipient=   $message->recipients()->create([
                'user_id' => $receiver,
            ]);

         // update last message for this conversation
         $conversation->update(['last_message_id' => $message->id]);
            broadcast(new MessageCreated($message,$recipient->user_id));

            DB::commit();
            return $message;
        }
        catch (\Exception $e) {
            DB::rollBack();

            return['error' => $e->getMessage()];
        }
    }

}

