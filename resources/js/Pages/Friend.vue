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
    <div>
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
    margin-bottom: 5px;
}
</style>
