<script setup>

import {ref} from 'vue'

const props = defineProps(
    [
        'notice',
        'friends',
        'csrfToken'
    ]
)
const notice = ref('')
const getRoute = (name) => {
    const routes = {
        online: '/friend/online-users',
        requestlist: '/friend/list',
    };
    return routes[name];
};

const friendId = ref('');
</script>
<template>
    <div class="flex flex-col items-center justify-center h-screen">
        <div class="container">
            <div class="notice" v-if="props.notice">
                {{ props.notice }}
            </div>

            <form method="POST" action="/friend/store">
                <label>輸入好友代碼</label>
                <input type="text" name="friend_id">
                <input type="hidden" v-model="props.csrfToken" name="_token">
                <button class="btn" type="submit">加入</button>
            </form>

            <div class="routeTo">
                <ul>
                    <li><a :href="getRoute('online')">在線使用者</a></li>
                    <li><a :href="getRoute('requestlist')">好友邀請</a></li>
                </ul>
            </div>

            <div class="friendlist">
                <ul v-if="props.friends.length > 0">
                    <li v-for="friend in props.friends" :key="friend.id">
                        <form method="GET" :action="'/friend/chat/' + friend.id">
                            {{ friend.name }}
                            <button type="submit">聊天</button>
                        </form>
                    </li>
                </ul>
                <p v-else>您尚未有好友！</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.notice{
    background-color:pink;
    box-sizing:border-box;
    color:red;margin-bottom: 5px;
}

.addFriend{
    margin-bottom: 5px;
}

.routeTo{
    margin-top: 30px;
    margin-bottom: 5px;
}

li{
    margin-top: 40px;
}

.notice .routeTo .friendlist{
    margin-top: 30px;
}

.container{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.container{
    padding: 50px;
    border: 1px solid black;
    width: 500px;
}

</style>
