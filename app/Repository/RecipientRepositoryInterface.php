<?php

namespace App\Repository;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Conversation;

interface RecipientRepositoryInterface
{



    public function create_recipient_conversation(Conversation $conversation,Message $meessage);

}
