<?php

namespace App\Http\Resources;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Debugbar;
use Illuminate\Support\Facades\Auth;

class FetchInformationConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->type=="group") {
            $label_peer = $this->members->isNotEmpty() == true ? $this->members()->where("id","<>",Auth::id())->first()->name : "anonymous";
            return [
                "Conversation_Id" => $this->id,
                "Conversation_Label" => $this->type == "group" ? $this->label : $label_peer,
                "Conversation_Description" => $this->description,
                "Conversation_Avatar" => $this->avatar,
                "Conversation_Type" => $this->type,
                "Conversation_Created_at" => $this->created_at->format("Y-m-d H:i:s"),
                "Conversation_Number_members" => $this->members()->count(),
                "Conversation_Members" => MemberResource::collection($this->members),

            ];
        }
        elseif ($this->type=="peer") {
            $other_user=$this->members()->where("id","<>",Auth::id())->first();
            $label_peer = $this->members->isNotEmpty() == true ? $other_user->name : "anonymous";
            $user=Auth::user();
            $ids_for_Auth_user_group=$user->conversations()->where("type","group")->pluck("id")->toarray();
            $members=Member::where("user_id",$other_user->id)->whereIn("conversation_id",$ids_for_Auth_user_group)->get();
            $conversations=[];
            foreach ($members as $member) {
                $conversations[]=$member->conversation;
            }
            return [
                "Conversation_Id" => $this->id,
                "Conversation_Label" =>$label_peer,
                "Conversation_Description" => $other_user->description,
                "Conversation_Avatar" => $other_user->avatar,
                "Conversation_Type" => $this->type,
                "Conversation_Created_at" => $this->created_at->format("Y-m-d H:i:s"),
                "Conversation_Number_Group_in_Common" =>$members->count(),
                "Details_Conversation_Group_in_Common" => FetchInformationConversationResource::collection($conversations),
            ];
        }
    }
}
