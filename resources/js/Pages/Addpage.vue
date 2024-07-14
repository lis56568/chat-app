<script setup>

import {onMounted, ref} from 'vue'

const props = defineProps(
    [
        'notice',
        'friends',
        'csrfToken',
        'user'
    ]
)
const visible = ref(false);
const fading = ref(false);

onMounted(() => {
    if (props.notice != null) {
        visible.value = true;
        setTimeout(() => {
            fading.value = true;
            setTimeout(() => {
                visible.value = false;
                fading.value = false;
            }, 1000); // 1 秒的淡化时间
        }, 3000); // 3 秒后开始淡化
    }
});
</script>
<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-200">
        <div class="container">
            <div class="notification-container" v-if="visible" :class="{'fade-out': fading}">
                <div class="notice flex items-center">
                    <img class="mr-1.5" width="20" height="20" src="https://img.icons8.com/ios-filled/50/721c24/cancel.png" alt="cancel"/>
                    {{ props.notice }}
                </div>
            </div>
            <div class="top flex flex-row justify-center items-center">
                <img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/333333/communication--v2.png" alt="communication--v2"/>
                <div class="flex mr-2 ml-5">
                    <div>
                        <span class="text-2xl">{{ user[0]['name'] }} # {{ user[0]['id'] }}</span>
                        <div class="flex flex-row items-center">
                            <div class="status-dot"></div>
                            <span class="dark:text-gray-500">Online</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mid flex justify-center">
                <form method="POST" action="/friend/store">
                    <div>
                        <input type="text" name="friend_id" class="addID" placeholder="輸入好友代碼">
                        <input type="hidden" v-model="props.csrfToken" name="_token">
                        <button class="btn" type="submit">送出邀請</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex justify-start mt-5 items-center">
            <img width="24" height="24" src="https://img.icons8.com/material-sharp/24/333333/arrow-pointing-left.png" alt="arrow-pointing-left"/>
            <a :href="route('home')" class="ml-3">Back</a>
        </div>
    </div>
</template>

<style scoped>
.notice{
    background-color:pink;
    box-sizing:border-box;
    color:red;
    margin-bottom: 5px;
}

.addFriend{
    margin-bottom: 5px;
}

li{
    margin-top: 30px;
}

.container{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: start;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.container{
    border-radius: 15px;
    height: 500px;
    width: 500px;
}

.btn{
    margin-right: 10px;
    border-radius: 5px;
    padding: 5px 10px;
    background-color: #333;
    color: #f5e9e9;
}

.btn:hover{
    transition: 0.5s;
    background-color: #181818;
    color: white;
}

.addID{
    border-radius: 5px;
    border: 1px solid #ececec;
    margin-right: 10px;
    width: 200px;
}

.top{
    width: 500px;
    height: 100px;
    border-bottom: 1px solid #e8e5e5;
    margin-bottom: 10px;
    margin-top: 20px;
    padding-bottom: 30px;
}

.mid{
    margin-top: 10px;
    height: 100px;
}

.friendlist{
    margin-bottom: 150px ;
    overflow-x: auto;
}

.btn{
    border-radius: 5px;
    padding: 7px 10px;
    background-color: #333;
    color: #f5e9e9;
    width: max-content;
    height: max-content;
}

.notice {
    background-color: #f8d7da;
    color: #721c24;
    padding: 15px;
    border: 1px solid transparent;
    border-radius: 4px;
}

.notification-container {
    position: absolute;
    top: 40px;
    display: flex;
    left: 50%;
    transform: translateX(-50%);
    border: 1px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: opacity 1s ease-out;
    background-color: #f8d7da;
}

.fade-out {
    opacity: 0;
}
.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
    background-color: green;
}

.chat{
    display: flex;
}

</style>
