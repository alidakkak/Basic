<?php

namespace App\Repository;

use Illuminate\Http\Request;

interface MessageRepositoryInterface
{
    public function store($request);
}
