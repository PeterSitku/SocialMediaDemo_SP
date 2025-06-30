<?php

namespace App\Http\Controllers\API;

use App\Models\PostLike;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function like(Request $request, $postId)
    {
        return PostLike::updateOrCreate(
            ['user_id' => Auth::id(), 'post_id' => $postId],
            ['type' => 'like']
        );
    }

    public function dislike(Request $request, $postId)
    {
        return PostLike::updateOrCreate(
            ['user_id' => Auth::id(), 'post_id' => $postId],
            ['type' => 'dislike']
        );
    }

    public function remove($postId)
    {
        PostLike::where('user_id', Auth::id())->where('post_id', $postId)->delete();
        return response()->noContent();
    }
}
