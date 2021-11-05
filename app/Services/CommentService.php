<?php

namespace App\Services;
use Throwable;
use App\Models\Comment;
use App\Models\Candidate;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentService 
{
    public static function storeComment($request,$candidateId){
        
        try
        {
            Candidate::findOrFail($candidateId)!=null;
        }catch(Throwable $e)
        {
            throw new NotFoundHttpException(message:'User with such id does not exist',code:404);
        }

        $comment = new Comment();
        $comment->content = $request->input('comment');
        $comment->candidate_id=$candidateId;
        $comment->save();
        return response()->json(['message'=>'Comment saved successfully','comment'=>$comment],200); 
    }
}