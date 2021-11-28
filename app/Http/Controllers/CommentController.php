<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentController extends Controller
{
    
    public function index(Request $request, $id=null){
        return CommentService::indexComment($id);
    }

    /**
     * @param CommentStoreRequest $request
     * @param id $candidateId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CommentStoreRequest $request, $candidateId)
    {
        return CommentService::storeComment($request, $candidateId);
    }

    /**
     * @param CommentUpdateRequest $request
     * @param mixed $commentId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CommentUpdateRequest $request, $commentId)
    {
        return CommentService::updateComment($request, $commentId);
    }
}
