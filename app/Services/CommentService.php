<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Candidate;

class CommentService
{


    /**
     * @param int $candidateId
     * @return JsonResponse
     */
    public static function indexComment(int $candidateId)
    {
        $candidate = Candidate::find($candidateId);
        $comments = $candidate->comments()->get();
        return response()->json(['comments' => $comments], 200);
    }


    /**
     * @param Request $request
     * @param int $candidateId
     * @return JsonResponse
     */
    public static function storeComment(Request $request, int $candidateId)
    {
        $comment = self::saveComment($request->input('content'), $candidateId);

        return response()->json(['message' => 'Comment saved successfully', 'comment' => $comment], 200);
    }

    /**
     * @param string $content
     * @param int $candidateId
     *
     * @return Comment
     */
    public static function saveComment(string $content, int $candidateId)
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->candidate_id = $candidateId;
        $comment->save();
        return $comment;
    }

    /**
     * @param Request $request
     * @param int $commentId
     *
     * @return JsonResponse
     * @throws Exception
     */
    public static function updateComment(Request $request, int $commentId)
    {
        $comment = Comment::find($commentId);

        $hasValue = false;
        if ($request->filled('content')) {
            $hasValue = true;
            $comment->update(['content' => $request->input('content')]);
        }
        if (!$hasValue) {
            throw new Exception('All valid input fields are empty', 406);
        }

        $comment = Comment::find($commentId);
        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 200);
    }
}
