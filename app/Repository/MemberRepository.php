<?php

namespace App\Repository;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Member;
use App\Models\Recipient;
use App\Traits\AttachFilesTrait;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Debugbar;
use Illuminate\Support\Facades\DB;

class MemberRepository implements MemberRepositoryInterface
{
    use GeneralTrait, AttachFilesTrait;

         public function create_member_and_put_admin_group_maker (Request $request,Conversation $conversation)
    {
        try {
            $users_id[0]["user_id"]=Auth::id();
            $users_id[0]["conversation_id"]=$conversation->id;
            $users_id[0]["role"]="admin";

            foreach ($request->users as $index=>$user_id)
            {
                $users_id[$index+1]["user_id"]=$user_id;
                $users_id[$index+1]["conversation_id"]=$conversation->id;
                $users_id[$index+1]["role"]="member";
            }
            //check if existing repeat value
            $is_repeat=false;
            foreach ($users_id as $user_id) {
                $count=0;
                foreach ($users_id as $user_id2) {
                    if ($user_id2["user_id"]== $user_id["user_id"]) {
                        $count++;
                        }
                    if ($count>1){
                        $is_repeat=true;
                        break;
                    }
                }
            }

            if ($is_repeat==false) {
                Member::insert($users_id);
            }
            return $is_repeat;
        }catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }

        public function create_members(Request $request,$conversation)
    {
        try {
            $is_repeat = false;
           $all_members=  array_merge( $conversation->members()->pluck("user_id")->toArray(),$request->users);
           $all_members= array_values(array_count_values($all_members));
           $all_members = array_filter($all_members, function($value) {
                return $value > 1;
            });

           if (!empty($all_members))
           {
               $is_repeat = true;
           }
            foreach ($request->users as $index=>$user_id)
            {
                $users_id[$index+1]["user_id"]=$user_id;
                $users_id[$index+1]["conversation_id"]=$conversation->id;
                $users_id[$index+1]["role"]="member";
            }
            if ($is_repeat == false) {
                Member::insert($users_id);
            }
            return $is_repeat;
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
        }
        public function deleted_member($ids, Conversation $conversation)
        {
        try{
           return $conversation->members()->detach([
               $ids
            ]);


        }
        catch (\Exception $e) {
        return $e->getMessage();
        }
        }
    public function check_is_existing_members($conversation)
    {
        try {
        $members=$conversation->members()->get();
        return $members;

    }
catch (\Exception $e) {
    return $e->getMessage();
    }

}

    public function check_is_existing_admin($conversation)
    {
        try {
            $members=$conversation->members()->where("role","admin")->get();
            return $members;

        }
        catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function make_admin_for_group_after_exit_admin(Conversation $conversation){
        try {
            $join_at_first_member=$conversation->members()->min("joined_at");
            $first_member=$conversation->members()->where("joined_at",$join_at_first_member)->first();
            $first_member->pivot->update(["role"=>"admin"]);
        } catch (\Exception $e) {
        return $e->getMessage();
        }
    }
    public function check_membership_for_conversation(Request $request,$user_id){
        try {
            $membership= Member::
            where("conversation_id",$request->conversation_id)
                ->where("user_id",$user_id)
                ->first();
            return $membership;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    }

