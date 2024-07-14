<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function room($friend_id)
    {
        $friend = User::where('id', $friend_id)->get(); // 使用 find 方法查找用户
        return Inertia::render('Room', ['friend' => $friend, 'id' => auth()->id(), 'csrfToken' => csrf_token()]); // 将用户对象传递给视图
    }

    public function sendMessage(Request $request)
    {
        $message = Chat::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message->message, $message->sender()->first(), $message->receiver()->first()))->toOthers();
    }
}
