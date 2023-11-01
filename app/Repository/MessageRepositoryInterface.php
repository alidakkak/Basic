<?php

namespace App\Repository;

use App\Models\Conversation;
use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
    public function store($request);
    public function create_message(Request$request,Conversation $conversation);
}
