<script setup>
const { friend } = defineProps(
    [
        'friend'
    ]
)
</script>
<template>
    <div class="container">
        <div class="chat">
            <div class="top">
                <img width="96" height="96" src="https://img.icons8.com/material-rounded/96/user-male-circle.png" alt="user-male-circle"/>
                <span>{{ friend[0]['name'] }}</span>
            </div>
            <div class="messages">

            </div>
            <div class="bottom">
                <input type="text" id="message" name="message" placeholder="type something to here....">
                <button id="sendBtn" name="sendBtn">
                    <img width="20" height="20" src="https://img.icons8.com/android/24/paper-plane.png" alt="paper-plane"/>
                </button>
            </div>
        </div>
    </div>
</template>
<style scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 100vh;
    margin: 0;
    background-color: #f0f0f0;
}

.chat {
    max-height: 600px;
    max-width: 700px;
    width: 100%;
    background-color: white; /* 可選：設置聊天區塊背景顏色 */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* 可選：設置陰影效果 */
    border-radius: 8px; /* 可選：設置圓角 */
    display: flex;
    flex-direction: column;
    margin-top: 50px; /* 向下移動一些 */
}

.top {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 20px;
}

.messages {
    padding: 20px;
    overflow-y: auto;
    max-height: 300px;
    height: 300px;
    border-top: 1px solid #ccc;
}

.bottom {
    display: flex;
    align-items: center;
    padding: 10px;
    border-top: 1px solid #ccc; /* 可選：設置頂部邊框 */
}

.bottom input[type="text"] {
    flex: 1;
    padding: 10px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.bottom button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.bottom button img {
    display: block;
}

.left.message > p,
.right.message > p
{
    display: inline-block;
    color: white;
    background-color: #aa00ff;
    padding: 7px 12px;
    border-radius: 18px;
    font-size: .9375rem;
}

.left.message, .right.message{
    display:flex;
    align-items: center;
    height: auto;
}
.left.message{
    justify-content: flex-start;
}

.right.message > img{
    display: inline-block;
}

.right.message{
    justify-content: flex-end;
}
</style>
