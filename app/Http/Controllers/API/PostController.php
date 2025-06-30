<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    public function __construct()
    {
        // Auth middleware az összes metódusra
        $this->middleware('auth:sanctum');
    }

    /**
     * Lista a posztokról (például a bejelentkezett user vagy barátai posztjai).
     */
    public function index()
    {
        $user = Auth::user();

        // Például: az összes posztot visszaadja, amit a user vagy a barátai készítettek
        $posts = Post::whereIn('user_id', $user->friends()->pluck('id')->push($user->id))
            ->with(['user', 'likes', 'shares'])
            ->latest()
            ->get();

        return response()->json($posts);
    }

    /**
     * Új poszt létrehozása.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:280', // példa, max 280 karakter
            // 'category_id' => 'nullable|exists:categories,id', ha van kategória
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            // 'category_id' => $request->category_id ?? null,
        ]);

        return response()->json($post, 201);
    }

    /**
     * Egy poszt megtekintése.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post); // policy check

        return response()->json($post->load(['user', 'likes', 'shares']));
    }

    /**
     * Poszt frissítése.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post); // policy check

        $request->validate([
            'content' => 'required|string|max:280',
            // 'category_id' => 'nullable|exists:categories,id',
        ]);

        $post->update([
            'content' => $request->content,
            // 'category_id' => $request->category_id ?? $post->category_id,
        ]);

        return response()->json($post);
    }

    /**
     * Poszt törlése.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post); // policy check

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }

    /**
     * Poszt megosztása (repost/share).
     * Ez egy egyszerű példa, az actual logikát a ShareController-ben érdemes kezelni, de ide is tehetjük.
     */
    public function share(Post $post)
    {
        // Pl. itt létrehozunk egy új "share" rekordot, ami a post_id és a user_id alapján tárolja a megosztást
        $user = Auth::user();

        // Ellenőrizzük, hogy még nem osztotta-e meg
        if ($post->shares()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'Already shared'], 409);
        }

        $post->shares()->create([
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Post shared']);
    }
}
