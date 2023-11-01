<?php

namespace App\Repository;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Conversation;

interface MemberRepositoryInterface
{
    public function create_member_and_put_admin_group_maker(Request $request,Conversation $conversation);
    public function create_members(Request $request,Conversation $conversation);
    public function deleted_member($ids,Conversation $conversation);

    public function check_is_existing_admin(Conversation $conversation);
    public function check_is_existing_members(Conversation $conversation);
    public function make_admin_for_group_after_exit_admin(Conversation $conversation);
    public function check_membership_for_conversation(Request $request);
}
