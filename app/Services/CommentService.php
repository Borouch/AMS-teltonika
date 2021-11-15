<?php

namespace App\Services;
use Throwable;
use App\Models\Comment;
use App\Models\Candidate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentService 
{
    /**
     * @param Request $request
     * @param int $candidateId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function storeComment($request,$candidateId){
        
        try
        {
            Candidate::findOrFail($candidateId);
        }catch(Throwable $e)
        {
            throw new NotFoundHttpException(message:'User with such id does not exist',code:404);
        }
        $comment = self::saveComment($request->input('content'),$candidateId);
        
        return response()->json(['message'=>'Comment saved successfully','comment'=>$comment],200); 
    }
    /**
     * @param string $content
     * @param int $candidateId
     * 
     * @return Comment
     */
    public static function saveComment($content,$candidateId)
    {
        $comment = new Comment();
        $comment->content = $content;
        $comment->candidate_id=$candidateId;
        $comment->save();
        return $comment;
    }
    /**
     * @param Request $request
     * @param int $commentId
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public static function updateComment($request,$commentId){
        
        try
        {
            $comment = Comment::findOrFail($commentId);
        }catch(Throwable $e)
        {
            throw new NotFoundHttpException(message:'Comment with such id does not exist',code:404);
        }

        if($request->filled('content'))
        {
            $comment->update(['content'=>$request->input('content')]);
        }
        if($request->filled('candidate_id'))
        {
            $comment->update(['candidate_id'=>$request->input('candidate_id')]);
        }
        $comment = Comment::find($commentId);
        return response()->json(['message'=>'Comment updated successfully','comment'=>$comment],200); 
    }
}