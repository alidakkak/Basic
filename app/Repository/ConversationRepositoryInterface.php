<?php

namespace App\Repository;

interface ConversationRepositoryInterface
{
    public function index() ;

    public function show($request);

    public function NumberOfUnreadMessage();

    public function markAsRead($request);

    public function delete($id);
}
