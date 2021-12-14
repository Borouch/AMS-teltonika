<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentIndexRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Services\CommentService;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentController extends Controller
{

    /**
     * @param CommentIndexRequest $request
     * @param int $candidateId
     * @return JsonResponse
     */
    public function index(CommentIndexRequest $request, int $candidateId)
    {
        return CommentService::indexComment($candidateId);
    }


    /**
     * @param CommentStoreRequest $request
     * @param int $candidateId
     * @return JsonResponse
     */
    public function store(CommentStoreRequest $request, int $candidateId)
    {
        return CommentService::storeComment($request, $candidateId);
    }


    /**
     * @param CommentUpdateRequest $request
     * @param int $commentId
     * @return JsonResponse
     * @throws Exception
     */
    public function update(CommentUpdateRequest $request, int $commentId)
    {
        return CommentService::updateComment($request, $commentId);
    }
}
