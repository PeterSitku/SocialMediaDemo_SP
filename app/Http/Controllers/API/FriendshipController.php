<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    public function sendRequest($friendId)
    {
        return Friendship::create([
            'user_id' => Auth::id(),
            'friend_id' => $friendId,
            'status' => 'pending',
        ]);
    }

    public function acceptRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        $friendship->update(['status' => 'accepted']);
        return $friendship;
    }

    public function rejectRequest($friendshipId)
    {
        $friendship = Friendship::findOrFail($friendshipId);
        $friendship->delete();
        return response()->noContent();
    }

    public function myFriends()
    {
        return Auth::user()->friends()->wherePivot('status', 'accepted')->get();
    }
}
