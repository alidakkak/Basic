<?php

namespace App\Repository;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Member;
use App\Models\Recipient;
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
        $conversations = $user->conversations()->with([
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


    public function show($request)
    {
        $conversation_id = $request->conversation_id;
        $conversation = Conversation::findOrFail($conversation_id);
        $messages = $conversation->messages()->orderByDesc("id")->get();
        $keys=["count_messages","messages"];
        $values=[$messages->count(),MessageResource::collection($messages)];
//        return response()->json(['message' => Debugbar::getData()["memory"]]);
        return  $this->returnData(200,$keys,$values);

    }


    public function NumberOfUnreadMessage()
    {

        $user = Auth::user();
        $unread_message = $user->unreadmessage();
        return $this->returnData("Number_Of_Unread_Messages", $unread_message);
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
        return $this->returnData("message", 'Messages marked as read');
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
    public function create_member_and_put_admin_group_maker (Request $request,Conversation $conversation)
    {
        try {
            $users_id[0]["user_id"]=Auth::id();
            $users_id[0]["conversation_id"]=$conversation->id;
            $users_id[0]["role"]="admin";

            foreach ($request->users as $index=>$user_id)
            {
                $users_id[$index+1]["user_id"]=$user_id;
                $users_id[$index+1]["conversation_id"]=$conversation->id;
                $users_id[$index+1]["role"]="member";
            }
            //check if existing repeat value
            $is_repeat=false;
            foreach ($users_id as $user_id) {
                $count=0;
                foreach ($users_id as $user_id2) {
                    if ($user_id2["user_id"]== $user_id["user_id"]) {
                        $count++;
                        }
                    if ($count>1){
                        $is_repeat=true;
                        break;
                    }
                }
            }

            if ($is_repeat==false) {
                Member::insert($users_id);
            }
            return $is_repeat;
        }catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }
}

