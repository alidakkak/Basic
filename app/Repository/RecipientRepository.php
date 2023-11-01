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

class RecipientRepository implements RecipientRepositoryInterface
{
    use GeneralTrait, AttachFilesTrait;


    public function create_recipient_conversation(Conversation $conversation,Message $message)
    {
        try {
       $ids_recipient=    $conversation->members()->select("user_id")->where("user_id","<>",Auth::id())->pluck("user_id")->toArray();
        $modify_ids_recipient []=[];
       foreach ($ids_recipient as $index=>$id_recipient)
            {
                $modify_ids_recipient[$index]["user_id"]=$id_recipient;
                $modify_ids_recipient[$index]["message_id"]=$message->id;

            }
            Recipient::insert($modify_ids_recipient);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}


