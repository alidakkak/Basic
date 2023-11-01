<?php

namespace App\Repository;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Conversation;

interface ConversationRepositoryInterface
{
    public function index() ;
    public function archived() ;

    public function show($request);

    public function NumberOfUnreadMessage();

    public function markAsRead($request);

    public function delete($id);

    public function create_conversation(Request $request,string $type):Conversation;

    public function fetch_conversation(Request $request) :Conversation;

    public function check_is_existing_conversation_between_two_user(Request $request);
    public function make_conversation_between_two_user(Request $request);

    public function update_last_message_conversation(Conversation $conversation,Message $message);

}
