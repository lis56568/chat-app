<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function room($friend_id)
    {
        $friend = User::where('id', $friend_id)->get(); // 使用 find 方法查找用户
        return Inertia::render('Room', ['friend' => $friend]); // 将用户对象传递给视图
    }
}
