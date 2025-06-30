<?php

namespace App\Http\Controllers\API;

use App\Models\PostShare;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostShareController extends Controller
{
    public function share($postId)
    {
        $post = Post::findOrFail($postId);

        // Ha már megosztotta, ne duplázzuk meg
        $share = PostShare::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return response()->json($share, 201);
    }

    public function unshare($postId)
    {
        PostShare::where('user_id', Auth::id())->where('post_id', $postId)->delete();
        return response()->noContent();
    }
}