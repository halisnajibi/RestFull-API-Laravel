<?php

namespace App\Http\Controllers;
use App\Http\Resources\ComentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function show($post_id){
        $comments = Comment::with('user:id,username,email')->where('post_id',$post_id)->get();
        return ComentResource::collection($comments);
    }

   public function store(Request $request){
        $validateData = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required'
        ]);
        $validateData['user_id'] = Auth::user()->id;
       $comment = Comment::create($validateData);
        return \response()->json([
            'comment_id' => $comment->id,
            'message' => 'created comment success'
        ]);
   }

   public function update(Request $request,$id){
    $validateData = $request->validate([
        'comments_content' => 'required'
    ]);
    $validateData['user_id'] = Auth::user()->id;
    Comment::where('id',$id)->update($validateData);
    return \response()->json([
        'comment_id' => $id,
        'message' => 'updated comment success'
    ]);
   }

   public function delete($id){
    Comment::destroy($id);
    return \response()->json([
        'comment_id' => $id,
        'message' => 'deleted comment success'
    ]);
   }
}
