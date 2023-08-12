<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $like = Like::where('post_id', $request->id)->where('user_id', Auth::user()->id)->get();

        if(count($like) > 0) {
            $like[0]->delete();
            return response()->json([
                'success' => true,
                'message' => 'Unliked'
            ]);
        }

        $like = new Like;
        $like->user_id = Auth::user()->id;
        $like->post_id = $request->id;
        $like->save();

        return response()->json([
            'success' => true,
            'message' => 'Liked'
        ]);
    }

    public function getUserLike($id)
    {
        $likes = Like::where('post_id', $id)->get();

        foreach ($likes as $like) {
            $like->user;
        }

        return response()->json([
            'success' => true,
            'likes' => $likes
        ], 200);
    }
}
