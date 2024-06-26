<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // 第一個用戶的 ID
            $table->unsignedBigInteger('friend_id'); // 第二個用戶的 ID
            $table->timestamps();

            // 設定 'user_id' 和 'friend_id' 兩個欄位作為主鍵
            $table->primary(['user_id', 'friend_id']);

            // 添加外鍵約束
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('friend_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friends');
    }
};
