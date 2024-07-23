<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function room($friend_id)
    {
        $friend = User::where('id', $friend_id)->get(); // 使用 find 方法查找用户
        $chats = Chat::with(['sender', 'receiver'])
            ->where(function ($query) use ($friend_id) {
                $query->where('sender_id', $friend_id)
                    ->where('receiver_id', auth()->id());
            })->orWhere(function ($query) use ($friend_id) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $friend_id);
            })
            ->orderBy('created_at', 'asc')
            ->get(['message', 'sender_id', 'receiver_id']);

        $record = $chats->map(function ($chat) {
            return [
                'message' => $chat->message,
                'sender' => $chat->sender,
                'receiver' => $chat->receiver
            ];
        });

        return Inertia::render('Room', ['friend' => $friend, 'id' => auth()->id(), 'csrfToken' => csrf_token(), 'record' => $record]); // 将用户对象传递给视图
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
