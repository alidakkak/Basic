<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMembersForGroupRequest;
use App\Http\Requests\DeletedMemberRequest;
use App\Http\Requests\ExitMemberRequest;
use App\Http\Requests\FetchInformationGroupRequest;
use App\Http\Requests\MakeGroupRequest;


use App\Http\Resources\ConversationResource;
use App\Http\Resources\FetchInformationConversationResource;
use App\Models\Conversation;
use App\Models\Member;
use App\Repository\ConversationRepositoryInterface;
use App\Repository\MemberRepositoryInterface;
use App\Traits\AttachFilesTrait;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    use AttachFilesTrait,GeneralTrait;
    protected $conversation;
    protected $members;
    public function __construct(ConversationRepositoryInterface $conversation,MemberRepositoryInterface $member) {
        $this->conversation = $conversation;
        $this->members = $member;
    }
    public function make_group(MakeGroupRequest $request)
    {
        DB::beginTransaction();
        try{
        $conversation=$this->conversation->create_conversation($request,$type="group");
        $is_repeat=$this->members->create_member_and_put_admin_group_maker($request,$conversation);
    if (!$is_repeat){
        DB::commit();
        $keys=["message","conversation"];
        $values=["group created",$conversation];
        return  $this->returnData(201,$keys,$values);
    }
    else{
        return $this->returnError(400,'there are repeat users');
    }
        } catch (\Exception $e) {
            DB::rollBack();
        return $e->getMessage();
        }
    }
    public function add_member_after_make_group(AddMembersForGroupRequest $request)
    {
        DB::beginTransaction();
        try{
          $conversation=  $this->conversation->fetch_conversation($request);
          $is_repeat=  $this->members->create_members($request,$conversation);
            if (!$is_repeat){
                DB::commit();
                $keys=["message"];
                $values=["members created"];
                return  $this->returnData(201,$keys,$values);
            }
            else{
                return $this->returnError(400,'there are repeat users');
              }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            }

    }
    public function delete_member_of_group(DeletedMemberRequest $request)
    {
        DB::beginTransaction();
        try{
            $conversation=  $this->conversation->fetch_conversation($request);
            $is_deleted=  $this->members->deleted_member($request->user_id,$conversation);
            if ($is_deleted){
                DB::commit();
                $keys=["message"];
                $values=["members deleted from conversation"];
                return  $this->returnData(204,$keys,$values);
            }
            else{
                return $this->returnError(400,'failed to delete members');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function exit_from_group(ExitMemberRequest $request)
    {
        DB::beginTransaction();
        try{
            $conversation=  $this->conversation->fetch_conversation($request);
            $is_deleted=  $this->members->deleted_member(Auth::id(),$conversation);
            if ($is_deleted){
                if($this->members->check_is_existing_members($conversation)->isNotEmpty() && $this->members->check_is_existing_admin($conversation)->isEmpty() )
                {
                    $this->members->make_admin_for_group_after_exit_admin($conversation);

                }
                DB::commit();
                $keys=["message"];
                $values=["Exit from conversation done"];
                return  $this->returnData(204,$keys,$values);
            }
            else{
                return $this->returnError(400,'failed to exit from conversation');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

}
