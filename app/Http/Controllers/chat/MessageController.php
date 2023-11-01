<?php

namespace App\Http\Controllers\chat;

use App\Events\MessageCreated;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Recipient;
use App\Repository\MessageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class MessageController extends Controller
{
    protected $message;
    public function __construct(MessageRepositoryInterface $message) {
        $this->message = $message;
    }

    public function store(Request $request) {
       return $this->message->store($request);
    }

    public function search(Request $request) {
        $keyword = $request->keyword;
        return Message::search($keyword)->get();
    }

    public function getMessageByDate()
    {
        $messages = Message::all()
            ->groupBy(function ($message) {
                return $message->created_at->format('Y-m-d');
            });
        return $messages;
    }





}

