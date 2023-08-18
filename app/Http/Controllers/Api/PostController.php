<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'desc' => 'required|max:1500',
        ]);

        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->desc = $request->desc;

        if($request->photo != '') {
            $photo = time().'.jpg';
            file_put_contents('storage/posts/'.$photo, base64_decode($request->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambah postingan',
            'post' => $post
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:100',
            'desc' => 'required|max:1500',
        ]);

        $post = Post::findOrFail($id);

        if(Auth::user()->id != $post->user_id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }

        $post->title = $request->title;
        $post->desc = $request->desc;
        $post->update();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah postingan'
        ], 201);
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);

        if(Auth::user()->id != $post->user_id){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ]);
        }
        
        if($post->photo != '') {
            Storage::delete('public/posts/'.$post->photo);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus postingan'
        ]);
    }

    public function posts()
    {
        $posts = Post::orderBy('id', 'desc')->get();

        foreach($posts as $post) {
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;

            foreach($post->likes as $like){
                if($like->user_id == Auth::user()->id) {
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

        foreach($posts as $post) {
            $post->user;
            $post['commentsCount'] = count($post->comments);
            $post['likesCount'] = count($post->likes);
            $post['selfLike'] = false;

            foreach($post->likes as $like){
                if($like->user_id == Auth::user()->id) {
                    $post['selfLike'] = true;
                }
            }
        }

        return response()->json([
            'success' => true,
            'posts' => $posts,
            'user' => $user
        ]);
    }

    public function postsPagination(Request $request)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 3);

        $posts = Post::orderBy('created_at', 'desc')
            ->paginate($size, ['*'], 'page', $page);

        $transformedPosts = [];

        foreach ($posts as $post) {
            $transformedPost = [
                "id" => $post->id,
                "user_id" => $post->user->id,
                "title" => $post->title,
                "desc" => $post->desc,
                "photo" => $post->photo,
                "created_at" => $post->created_at,
                "updated_at" => $post->updated_at,
                "commentsCount" => count($post->comments),
                "likesCount" => count($post->likes),
                "selfLike" => false,
                "user" => [
                    "id" => $post->user->id,
                    "name" => $post->user->name,
                    "lastname" => $post->user->lastname,
                    "photo" => $post->user->photo,
                    "email" => $post->user->email,
                    "email_verified_at" => $post->user->email_verified_at,
                    "created_at" => $post->user->created_at,
                    "updated_at" => $post->user->updated_at,
                ],
                "comments" => $post->comments,
                "likes" => $post->likes
            ];

            foreach ($post->likes as $like) {
                if ($like->user_id == Auth::user()->id) {
                    $transformedPost['selfLike'] = true;
                }
            }

            $transformedPosts[] = $transformedPost;
        }

        $response = [
            'page' => $page,
            'per_page' => $size,
            'total' => $posts->total(),
            'total_pages' => $posts->lastPage(),
            'data' => $transformedPosts,
        ];

        return response()->json($response);
    }
}