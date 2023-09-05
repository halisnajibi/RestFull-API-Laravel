<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ShowPostResource;

class PostController extends Controller
{
   public function index()
   {
      $posts = Post::with(['user','comments'])->get();
      return PostResource::collection($posts);
   }

   public function show($id)
   {
      $post = Post::with('user:id,username,email')->findOrFail($id);
      return new ShowPostResource($post);
   }

   public function store(Request $request){
     $validateData = $request->validate([
      'title' => 'required|max:255',
      'news_content' => 'required',
      'image' => 'image|file|max:1024'
     ]);
     if($request->file('image')){
         $validateData['image'] =  $request->file('image')->store('post-images');
      }

     $validateData['user_id'] = Auth::user()->id;
     $post = Post::create($validateData);
     return \response()->json([
      'id' => $post->id,
      'message' => 'create post success'
     ]);
   }

   public function update(Request $request,$id){
      $post = Post::find($id);
      $validateData = $request->validate([
         'title' => 'required|max:255',
         'news_content' => 'required',
         'image' => 'image|file|max:1024',
        ]);
        if($request->file('image')){
         if($post->image != null){
            Storage::delete($post->image);
         }
         $validateData['image'] = $request->file('image')->store('post-images');
     }
        Post::where('id',$id)->update($validateData);
        return \response()->json([
         'id' => $id,
         'message' => 'updated post success'
        ]);
   }

   public function delete($id){
     $post = Post::find($id);
     if($post->image){
      Storage::delete($post->image);
     }
      Post::destroy($id);
      return \response()->json([
         'id' => $id,
         'message' => 'deleted post success'
        ]);
   }
   

   private function _generateRandomString($length = 40) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[random_int(0, $charactersLength - 1)];
      }
      return $randomString;
  }
}
