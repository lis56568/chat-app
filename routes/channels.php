<?php
use App\Models\Friend;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{num1}.{num2}', function ($user, $num1, $num2) {
    return $user;
});
