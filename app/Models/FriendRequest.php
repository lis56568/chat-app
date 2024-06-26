<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FriendRequest extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id'];

    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function receiver(): HasOne
    {
        return $this->hasOne(User::class, 'id' ,'receiver_id');
    }
}
