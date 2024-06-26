<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     * return view 朋友列表頁面
     */
    public function index()
    {
        $temps = auth()->user()->friends()->get();
        $friends = [];
        foreach ($temps as $temp) {
            $user = User::where('id', $temp->friend_id)->get();
            if ($user) {
                $friends[] = $user->first();
            }
        }
        return Inertia::render('Friend', [
            'friends' => $friends,
            'notice' => session()->get('notice'), // 传递通知信息
            'csrfToken' => csrf_token()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 新增好友
        // return view('');
    }

    /**
     * Store a newly created resource in storage.
     * 加入好友
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);
        $id = $request->input('friend_id');
        if ($id == auth()->id()) {
            return Inertia::location('/friend')->with('notice', '不可加入自己');
        }
        $existFriend = Friend::where('user_id', auth()->id())->where('friend_id', $id)->exists();
        if ($existFriend) {
            return redirect()->route('home')->with('notice', '已在好友名單中');
        }
        $existingRequest = FriendRequest::where('receiver_id', $id)
            ->where('sender_id', auth()->id())
            ->exists();
        if ($existingRequest) {
            return redirect()->route('home')->with('notice', '無法重複提出交友邀請!');
        }
        $data['sender_id'] = auth()->id();
        $data['receiver_id'] = $id;
        FriendRequest::create($data);

        return Inertia::location('/friend')->with('notice', '加入成功');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $id = auth()->id();
        $friendRequests = FriendRequest::where('receiver_id', $id)->get();
        $senders = [];
        foreach ($friendRequests as $request) {
            $senders[] = $request->sender;
        }

        return Inertia::render('FriendInvite', ['senders' => $senders]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user_id = auth()->id();
        $friend_id = $request->input('friend_id');

        Friend::where('user_id', $user_id)->where('friend_id', $friend_id)->delete();
        Friend::where('user_id', $friend_id)->where('friend_id', $user_id)->delete();
    }

    // 接受好友邀請
    public function accept(Request $request)
    {
        $user_id = auth()->id();
        $friend_id = $request->input('friend_id');
        $sender = [];
        FriendRequest::where('sender_id', $friend_id)->where('receiver_id', $user_id)->delete();

        $data = [
            'user_id' => $user_id,
            'friend_id' => $friend_id,
        ];
        Friend::create($data);

        $data = [
            'user_id' => $friend_id,
            'friend_id' => $user_id,
        ];
        Friend::create($data);

        return Inertia::render('FriendInvite', ['senders' => []]);
    }

    //拒絕好友邀請
    public function cancel(Request $request)
    {
        $sender_id = $request->input('friend_id');
        $receiver_id = auth()->id();
        FriendRequest::where('sender_id', $sender_id)->where('receiver_id', $receiver_id)->delete();

        return Inertia::render('FriendInvite', ['senders' => []]);
    }

    public function onlineUsers()
    {
        return Inertia::render('Online', [
            'users' => User::where('id', '!=', auth()->id())->get(),
            'user_id' => auth()->id()
        ]);
    }
}
