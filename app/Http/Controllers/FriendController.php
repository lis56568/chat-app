<?php

namespace App\Http\Controllers;

use App\Models\Chat;
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
    public function index(): \Inertia\Response
    {
        $temps = auth()->user()->friends()->get();
        $friends = [];
        $msg_last = [];
        foreach ($temps as $temp) {
            $user = User::where('id', $temp->friend_id)->get();
            if ($user) {
                $msg = Chat::with(['sender', 'receiver'])
                    ->where(function ($query) use ($user) {
                        $query->where('sender_id', $user->first()->id)
                            ->where('receiver_id', auth()->id());
                    })->orWhere(function ($query) use ($user) {
                        $query->where('sender_id', auth()->id())
                            ->where('receiver_id', $user->first()->id);
                    })
                    ->orderBy('created_at', 'desc')
                    ->first(['message', 'sender_id', 'receiver_id']);
                if($msg){
                    $user->first()->msg = $msg->message;
                }
                $friends[] = $user->first();

            }

        }
        $user = User::where('id', auth()->id())->get();

        return Inertia::render('Friend', [
            'friends' => $friends,
            'notice' => session()->get('notice'), // 传递通知信息
            'csrfToken' => csrf_token(),
            'user' => $user,
            'msg_last' => $msg_last,
        ]);
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
            return Inertia::location('/friend/addpage')->with('notice', '不可加入自己');
        }
        $existFriend = Friend::where('user_id', auth()->id())->where('friend_id', $id)->exists();
        if ($existFriend) {
            return redirect()->route('addpage')->with('notice', '已在好友名單中');
        }
        $existingRequest = FriendRequest::where('receiver_id', $id)
            ->where('sender_id', auth()->id())
            ->exists();
        if ($existingRequest) {
            return redirect()->route('addpage')->with('notice', '無法重複提出交友邀請!');
        }
        $data['sender_id'] = auth()->id();
        $data['receiver_id'] = $id;
        FriendRequest::create($data);

        return Inertia::location('/friend/addpage')->with('notice', '加入成功');
    }

    /**
     * Display the specified resource.
     */
    public function show(): \Inertia\Response
    {
        $id = auth()->id();
        $friendRequests = FriendRequest::where('receiver_id', $id)->get();
        $senders = [];
        foreach ($friendRequests as $request) {
            $senders[] = $request->sender;
        }

        return Inertia::render('FriendInvite', ['senders' => $senders, 'user' => auth()->user()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): void
    {
        $user_id = auth()->id();
        $friend_id = $request->input('friend_id');

        Friend::where('user_id', $user_id)->where('friend_id', $friend_id)->delete();
        Friend::where('user_id', $friend_id)->where('friend_id', $user_id)->delete();
    }

    // 接受好友邀請
    public function accept(Request $request): \Inertia\Response
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
        $id = auth()->id();
        $friendRequests = FriendRequest::where('receiver_id', $id)->get();
        $senders = [];
        foreach ($friendRequests as $request) {
            $senders[] = $request->sender;
        }

        return Inertia::render('FriendInvite', ['senders' => $senders, 'user' => auth()->user()]);
    }

    //拒絕好友邀請
    public function cancel(Request $request): \Inertia\Response
    {
        $sender_id = $request->input('friend_id');
        $receiver_id = auth()->id();
        FriendRequest::where('sender_id', $sender_id)->where('receiver_id', $receiver_id)->delete();
//        return Inertia::location('/FriendInvite', ['senders' => []]);
        return Inertia::render('FriendInvite', ['senders' => []]);
    }

    public function onlineUsers(): \Inertia\Response
    {
        return Inertia::render('Online', [
            'users' => User::where('id', '!=', auth()->id())->get(),
            'user_id' => auth()->id()
        ]);
    }

    public function getPortal(): \Inertia\Response
    {
        return Inertia::render('Friendportal',[]);
    }

    public function addPage(): \Inertia\Response
    {
        $temps = auth()->user()->friends()->get();
        $friends = [];
        foreach ($temps as $temp) {
            $user = User::where('id', $temp->friend_id)->get();
            if ($user) {
                $friends[] = $user->first();
            }
        }
        $user = User::where('id', auth()->id())->get();

        return Inertia::render('Addpage', [
            'friends' => $friends,
            'notice' => session()->get('notice'), // 传递通知信息
            'csrfToken' => csrf_token(),
            'user' => $user,
        ]);
    }
}
