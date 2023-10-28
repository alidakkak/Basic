<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeGroupRequest;


use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Member;
use App\Repository\ConversationRepositoryInterface;
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

    public function __construct(ConversationRepositoryInterface $conversation) {
        $this->conversation = $conversation;
    }

    public function make_group(MakeGroupRequest $request)
    {
        DB::beginTransaction();
        try{
        $conversation=$this->conversation->create_conversation($request,$type="group");
        $is_repeat=$this->conversation->create_member_and_put_admin_group_maker($request,$conversation);
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
    public function add_member_after_make_group()
    {
        DB::beginTransaction();
        try{








        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            }

    }



}
