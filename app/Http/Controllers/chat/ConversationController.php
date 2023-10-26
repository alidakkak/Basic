<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Member;
use App\Models\Message;
use App\Models\Recipient;
use App\Repository\ConversationRepositoryInterface;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    use GeneralTrait;

    protected $conversation;

    public function __construct(ConversationRepositoryInterface $conversation) {
        $this->conversation = $conversation;
    }

    public function index()
    {
        return $this->conversation->index();
    }


    public function show(Request $request)
    {
        return $this->conversation->show($request);
    }


    public function NumberOfUnreadMessage()
    {
        return $this->conversation->NumberOfUnreadMessage();
    }


    public function markAsRead(Request $request)
    {
       return $this->conversation->markAsRead($request);
    }

    public function delete($id)
    {
        return $this->conversation->delete($id);
    }

}
