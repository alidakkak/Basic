<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationRequest;
use App\Http\Requests\UserRequest;
use App\Models\Member;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\MemberRepositoryInterface;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureController extends Controller
{
    use GeneralTrait;
    public function __construct(ConversationRepositoryInterface $conversation,MemberRepositoryInterface $member) {
        $this->conversation = $conversation;
        $this->members = $member;
    }
    public function pinned_unpinned(ConversationRequest $request)
    {
        try {
            $membership=$this->members->check_membership_for_conversation($request);
            if (!$membership)
            {
                return response()->json([["message"=>"not found conversation"]]);
            }
            $new_status= Member::
            where("conversation_id",$request->conversation_id)
                ->where("user_id",Auth::id())->update(["is_archived"=>0,"is_pinned"=>!$membership->is_pinned]);
            $new_status= Member::
            where("conversation_id",$request->conversation_id)
                ->where("user_id",Auth::id())->first();
            $keys=["status","member"];
            $values=[202, $new_status];
            return  $this->returnData(204,$keys,$values);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function archived_unarchived(ConversationRequest $request)
    {

        try {
            $membership=$this->members->check_membership_for_conversation($request);

            if (!$membership)
            {
                return response()->json([["message"=>"not found conversation"]]);
            }
            $new_status= Member::
                 where("conversation_id",$request->conversation_id)
                ->where("user_id",Auth::id())->update(["is_archived"=>!$membership->is_archived,"is_pinned"=>0]);
            $new_status= Member::
            where("conversation_id",$request->conversation_id)
                ->where("user_id",Auth::id())->first();
            $keys=["status","member"];
            $values=[202, $new_status];
            return  $this->returnData(204,$keys,$values);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function muted_unmute(ConversationRequest $request)
    {
        try {
            $membership=$this->members->check_membership_for_conversation($request);

            if (!$membership)
            {
                return response()->json([["message"=>"not found conversation"]]);
            }
            $new_status= Member::
            where("conversation_id",$request->conversation_id)
                ->where("user_id",Auth::id())->update(["is_mute"=>!$membership->is_mute]);
            $new_status= Member::
            where("conversation_id",$request->conversation_id)
                ->where("user_id",Auth::id())->first();
            $keys=["status","member"];
            $values=[202, $new_status];
            return  $this->returnData(204,$keys,$values);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
}
    public function block_unblock(UserRequest $request)
    {
        try {

            $conversation= $this->conversation->check_is_existing_conversation_between_two_user($request);
            //check if  there is no conversation => create new conversation
            if (!$conversation) {
                $conversation= $this->conversation->make_conversation_between_two_user($request);
            }
            $current_status= Member::
            where("conversation_id",$conversation->id)
                ->where("user_id",Auth::id())
                ->first();
            if (!$current_status)
            {
                return response()->json([["message"=>"not found conversation"]]);
            }
            $new_status= Member::
            where("conversation_id",$conversation->id)
                ->where("user_id",Auth::id())->update(["is_block"=>!$current_status->is_block]);
            $new_status= Member::
            where("conversation_id",$conversation->id)
                ->where("user_id",Auth::id())->first();
            $keys=["status","member"];
            $values=[202, $new_status];
            return  $this->returnData(204,$keys,$values);
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
