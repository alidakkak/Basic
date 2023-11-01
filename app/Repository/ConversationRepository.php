<?php

namespace App\Repository;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Member;
use App\Models\Message;
use App\Models\Recipient;
use App\Models\User;
use App\Traits\AttachFilesTrait;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Debugbar;
use Illuminate\Support\Facades\DB;

class ConversationRepository implements ConversationRepositoryInterface
{
    use GeneralTrait, AttachFilesTrait;
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->pinnedconversations()->with([
            "lastMessage" => function ($builder) {
                $builder->with(["sender" => function ($builder) {
                    $builder->select("id", "name");
                }]);
            },
            "members" => function ($builder) use ($user) {
                $builder->where("user_id", "<>", $user->id)->select("name");
            }
        ])->withCount([
            'recipients as new_messages' => function ($builder) use ($user) {
                $builder->where('recipients.user_id', $user->id)
                    ->whereNull('read_at');
            }
        ])->get();
        $keys=["conversations"];
        $values=[ConversationResource::collection($conversations)];
//        return response()->json(['message' => Debugbar::getData()["memory"]]);
        return  $this->returnData(200,$keys,$values);
    }
    public function archived()
    {
        $user = Auth::user();
        $conversations = $user->archivedconversations()->with([
            "lastMessage" => function ($builder) {
                $builder->with(["sender" => function ($builder) {
                    $builder->select("id", "name");
                }]);
            },
            "members" => function ($builder) use ($user) {
                $builder->where("user_id", "<>", $user->id)->select("name");
            }
        ])->withCount([
            'recipients as new_messages' => function ($builder) use ($user) {
                $builder->where('recipients.user_id', $user->id)
                    ->whereNull('read_at');
            }
        ])->get();
        $keys=["conversations"];
        $values=[ConversationResource::collection($conversations)];
//        return response()->json(['message' => Debugbar::getData()["memory"]]);
        return  $this->returnData(200,$keys,$values);
    }






    public function delete($id)
    {
        Recipient::where([
            'user_id' => Auth::id(),
            'message_id' => $id
        ])->delete();
        return [
            'message' => 'Deleted SuccesFully'
        ];
    }
    public function create_conversation (Request $request,$type) :Conversation
    {
        try {
            $file_path=null;
            if ($request->hasFile('image')) {
                $file_path = $this->uploade_image($request->name, "avatar", $request->file("image"));
            }
            $user = Auth::user();
        $conversation=  Conversation::create([
                "user_id" => $user->id,
                "description" => $request->description,
                "label" => $request->label,
                "type" => $type,
                "avatar"=>$file_path,
            ]);
            return $conversation;
        }catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }
    public function fetch_conversation(Request $request) :Conversation
    {
        try {
            $conversation = Conversation::find($request->conversation_id);
            return $conversation;

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function check_is_existing_conversation_between_two_user(Request $request)
{
    try {
    $sender = Auth::user();
    $receiver = User::find($request->post('user_id'));
    $conversation=  $sender
        ->conversations()
        ->where("type","peer")
        ->whereHas('members',function ($builder) use ($receiver){
            $builder->where('user_id',$receiver->id);
        })
        ->first();
    return $conversation;

    } catch (\Exception $e) {
            return $e->getMessage();
        }
}
    public function make_conversation_between_two_user(Request $request)
    {

        try {
            $sender = Auth::user();
            $receiver = User::find($request->post('user_id'));
            $conversation = Conversation::create([
                "user_id" => $sender->id,
                "type" => "peer",
            ]);
            // add members for this conversation
            $conversation->members()->attach([
                $sender->id => ['joined_at' => now()],
                $receiver->id => ['joined_at' => now()],
            ]);
            return $conversation;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function update_last_message_conversation(Conversation $conversation,Message $message)
    {

        try {

         return   $conversation->update(['last_message_id' => $message->id]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}


