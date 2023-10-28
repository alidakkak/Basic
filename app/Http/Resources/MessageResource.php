<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Debugbar;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "Message_Id"=>$this->id,
            "Message_Type"=>$this->type,
            "Message_Body"=>$this->body,
            "Message_Created_at"=>$this->created_at->format('Y-m-d H:i:s'),
            "Message_Sender_Id"=>$this->sender->id,
            "Message_Sender_Name"=>$this->sender->name,

        ];
    }
}
