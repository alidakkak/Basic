<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Debugbar;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     $label_peer=$this->members->isNotEmpty()==true?$this->members[0]->name :"anonymous";
        return [
            "Conversation_Id"=>$this->id,
            "Conversation_Label"=>$this->type=="group"?$this->label :$label_peer,
            "Conversation_Type"=>$this->type,
            "Conversation_Number_New_Messages"=>$this->new_messages,
            "Conversation_Created_at"=>$this->created_at->format("Y-m-d H:i:s"),
            "Conversation_Last_message"=> $this->lastMessage->body=='Message deleted'?$this->lastMessage->body:
                [
                    "Last_message_Id"=>$this->lastMessage->id,
                    "Last_message_Type"=>$this->lastMessage->type,
                    "Last_message_Body"=>$this->lastMessage->body,
                    "Last_message_Sender"=> $this->lastMessage->sender->name,
                ],

        ];
    }
}
