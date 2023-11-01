<?php

namespace App\Http\Controllers\chat;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\MessageRequest;
use App\Models\Conversation;
use App\Models\HideMessage;
use App\Models\Recipient;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\MemberRepositoryInterface;
use App\Repository\MessageRepositoryInterface;
use App\Repository\RecipientRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class MessageController extends Controller
{
    protected $message,$conversation,$members,$recipient;
    public function __construct(MessageRepositoryInterface $message,ConversationRepositoryInterface $conversation,MemberRepositoryInterface $member,RecipientRepositoryInterface $recipient) {
        $this->message = $message;
        $this->conversation = $conversation;
        $this->members = $member;
        $this->recipient=$recipient;
    }

    public function store(CreateMessageRequest $request) {
        DB::beginTransaction();
        try {
            if ($request->conversation_id){
                $membership=$this->members->check_membership_for_conversation($request);
                if (!$membership) {
                    return response()->json(["message" => " you  are not a member of this conversation"]);
                }
                $conversation = $this->conversation->fetch_conversation($request);
                $message = $this->message->create_message($request, $conversation);
                if (!$message) {
                    return response()->json(["message" => " error"]);
                }
                $this->recipient->create_recipient_conversation($conversation, $message);
                $this->conversation->update_last_message_conversation($conversation, $message);
//            broadcast(new MessageCreated($message,$recipient->user_id));
                DB::commit();
                return $message;

            }
            elseif ($request->user_id) {

                $conversation = $this->conversation->check_is_existing_conversation_between_two_user($request);
                if (!$conversation) {
                    foreach ($conversation->members as $member) {
                        if ( $member->pivot->is_block == 1) {
                            return response()->json(["status"=>403,"message"=>"blocked"]);
                        }
                    }}
                //check if  there is no conversation => create new conversation
                if (!$conversation) {
                    $conversation = $this->conversation->make_conversation_between_two_user($request);
                }
                $message = $this->message->create_message($request, $conversation);
                if (!$message) {
                    return response()->json(["message" => " error"]);
                }
                $this->recipient->create_recipient_conversation($conversation, $message);
                $this->conversation->update_last_message_conversation($conversation, $message);
//            broadcast(new MessageCreated($message,$recipient->user_id));
                DB::commit();
                return $message;
                }

        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function delete(MessageRequest $request) {
        try {
            $user=Auth::user();
          $message=$user->messages()->find($request->message_id);
          if (!$message){
              return response()->json(["message" => "not found"]);
          }
            $message->delete();
            return response()->json(
                ["status"=>204,
                  "message"=>"delete message"
                ]
            );
        }
        catch (\Exception $e) {

            return $e->getMessage();
        }}
    public function delete_for_me(MessageRequest $request) {
        try {
          $user=Auth::user();
          $user->hidemessage()->sync([
              "message_id"=>$request->message_id,
          ]);
            return response()->json(
                ["status"=>200,
                    "message"=>"delete message for me"
                ]
            );
        }
        catch (\Exception $e) {

            return $e->getMessage();
        }}
}

