<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'comment' => 'required|max:255'
        ]);

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->id;
        $comment->comment = $request->comment;
        $comment->save();
        $comment->user;

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditambah',
            'comment'=> $comment
        ], 201);
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);

        if($comment->user_id != Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorize access'
            ]);
        }
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar dihapus'
        ]);
    }

    public function comments(Request $request)
    {
        $comments = Comment::where('post_id', $request->id)->get();
        
        // menampilkan semua komentar
        foreach($comments as $comment){
            $comment->user;
        }

        return response()->json([
            'success' => true,
            'comments' => $comments
        ], 201);
    }
}
