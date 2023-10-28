<?php

namespace App\Repository;
use Illuminate\Http\Request;
use App\Models\Conversation;

interface ConversationRepositoryInterface
{
    public function index() ;

    public function show($request);

    public function NumberOfUnreadMessage();

    public function markAsRead($request);

    public function delete($id);

    public function create_conversation(Request $request,string $type):Conversation;

    public function create_member_and_put_admin_group_maker(Request $request,Conversation $conversation);

    public function check_is_already_exists_conversation(Request $request);

}
