<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create(Request $request){

        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc = $request->desc;

        //check if post has photo
        if($request->photo != ''){
            //choose a unique name for photo
            $photo = time().'.jpg';
            file_put_contents('storage/posts/'.$photo,base64_decode($request->photo));
            $post->photo = $photo;
        }
        
        $post->save();
        $post->user;
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengunggah postingan',
            'post' => $post
        ], 201);
    }

    public function update(Request $request)
    {
        $post = Post::findOrFail($request->id);

        if(Auth::user()->id != $post->user_id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $post->desc = $request->desc;
        $post->update();
        return response()->json([
            'success' => true,
            'message' => 'Postingan diubah'
        ], 201);
    }

    public function delete(Request $request)
    {
        $post = Post::findOrFail($request->id);

        if(Auth::user()->id != $post->user_id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        if($post->photo != ''){
            Storage::delete('public/posts/'.$post->photo);
        }

        $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Postingan dihapus'
        ]);
    }

    public function posts()
    {
        $posts = Post::orderBy('id','desc')->get();
        foreach($posts as $post){

            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;
            foreach($post->likes as $like){
                if($like->user_id == Auth::user()->id){
                    $post['selfLike'] = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }

    public function myPosts()
    {
        $posts = Post::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'posts' => $posts,
            'user' => $user
        ]);
    }
}
