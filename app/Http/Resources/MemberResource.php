<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Debugbar;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            "Member_Id"=>$this->id,
            "Member_Name"=>$this->name,
            "Member_Status"=>$this->status,
            "Member_Image"=>$this->image,
            "Member_Name"=>$this->name,
            "Member_Role"=>$this->pivot->role,
            "Message_join_at"=>$this->pivot->joined_at,


        ];
    }
}
