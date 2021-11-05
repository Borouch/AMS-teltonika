<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\CommentStoreRequest;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request,$candidateId)
    {
        return CommentService::storeComment($request,$candidateId);
    }
}
