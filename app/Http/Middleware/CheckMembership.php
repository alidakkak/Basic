<?php

namespace App\Http\Middleware;

use App\Models\Conversation;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMembership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$roles): Response
    {
        $user=auth()->user();
        $conversation=Conversation::find($request->conversation_id);
        if (!$conversation)
        {
            return response()->json([
                "message" =>"enter conversation_id failed"
            ]);
        }
        $member= Member::
        where("conversation_id",$conversation->id)
            ->where("user_id",$user->id)
            ->whereIn("role",$roles)->first();
        if (!$member)
        {
        return response()->json([
            "message" =>"your membership doesnt allow you"
        ]);
        }
        return $next($request);
    }
}
