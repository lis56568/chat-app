<script setup>

import { onMounted, ref} from "vue";


const props = defineProps(
    [
        'notice',
        'friends',
        'csrfToken',
        'user',
        'msg_last'
    ]
)


const last_msg = ref({});
const getRoute = (name) => {
    const routes = {
        addpage: '/friend/addpage',
        requestlist: '/friend/list',
    };
    return routes[name];
};

const get_chatroom = (id) => {
    window.location.href = `friend/chat/${id}`;
}

const get_image = (url) =>{
    const image = ref('/storage/' + url)
    return image.value.replace('/public', '');
}


const set_channel = () => {
        props.friends.forEach(friend => {
            const num1 = Math.min(props.user[0]['id'], friend['id']);
            const num2 = Math.max(props.user[0]['id'], friend['id']);
            const channel = `chat.${num1}.${num2}`;
            console.log('test');
            Echo.private(channel)
                .listen('MessageSent', (e) => {
                    console.log('websocket connect')
                    console.log('Received message:', e);
                    friend['msg'] = "";
                    last_msg.value[friend.id] = e.message;
                });
        });
};

onMounted(() => {
    set_channel();
})


</script>
<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-200">
        <div class="container">
            <div class="top flex flex-row justify-center items-center">
                <div v-if="user[0]['avatar'] === null">
                    <img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/333333/communication--v2.png" alt="communication--v2"/>
                </div>
                <div v-else>
                    <img :src="get_image(props.user[0]['avatar'])" alt="avatar" class="rounded-full h-16 w-16 object-cover">
                </div>
                <div class="flex mr-2 ml-5">
                    <div>
                        <span class="text-xl">{{ user[0]['name'] }} # {{ user[0]['id'] }}</span>
                        <div class="flex flex-row items-center">
                            <div class="status-dot"></div>
                            <span class="dark:text-gray-500">Online</span>
                        </div>
                    </div>
                </div>
                <div class="flex ml-10">
                    <div class="btn flex items-center">
                        <img class="mr-2 image" width="20" height="20" src="https://img.icons8.com/ios-filled/50/FFFFFF/new-post.png" alt="new-post"/>
                        <a :href="getRoute('requestlist')" class="text-xs">好友邀請</a>
                    </div>
                    <div class="flex btn items-center">
                        <img class="mr-2 image" width="20" height="20" src="https://img.icons8.com/external-tanah-basah-glyph-tanah-basah/48/FFFFFF/external-add-user-social-media-ui-tanah-basah-glyph-tanah-basah.png" alt="external-add-user-social-media-ui-tanah-basah-glyph-tanah-basah"/>
                        <a :href="getRoute('addpage')" class="text-xs">加入好友</a>
                    </div>
                </div>
            </div>

            <div class="friendlist">
                <ul v-if="props.friends.length > 0">
                    <li v-for="friend in props.friends" :key="friend['id']" class="chat">
                        <div v-if="friend['avatar'] === null" class="flex items-center friendbt" @click="get_chatroom(friend['id'])">
                            <div class="flex items-center bt-line">
                                <img class="mr-8 mt h-12 w-12" src="https://img.icons8.com/external-kiranshastry-lineal-kiranshastry/64/external-user-interface-kiranshastry-lineal-kiranshastry.png" alt="external-user-interface-kiranshastry-lineal-kiranshastry"/>
                                <div>
                                    <div class="text">
                                        {{ friend['name'] }}
                                    </div>
                                    <p v-if="friend['msg']" class="last-msg">
                                        {{ friend['msg'] }}
                                    </p>
                                    <p v-else class="last-msg">
                                        {{ last_msg[friend['id']]  || '點我發送訊息' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="flex items-center friendbt" @click="get_chatroom(friend['id'])">
                            <div class="flex items-center">
                                <img class="mr-8 h-12 w-12 rounded-full object-cover bt-line" :src="get_image(friend['avatar'])" alt="external-user-interface-kiranshastry-lineal-kiranshastry"/>
                                <div>
                                    <div class="text">
                                        {{ friend['name'] }}
                                    </div>
                                    <p v-if="friend['msg']" class="last-msg">
                                        {{ friend['msg'] }}
                                    </p>
                                    <p v-else class="last-msg">
                                        {{ last_msg[friend['id']]  || '點我發送訊息' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <p v-else>您尚未有好友！</p>
            </div>
        </div>
        <div class="flex justify-start mt-5 items-center">
            <img width="24" height="24" src="https://img.icons8.com/material-sharp/24/333333/arrow-pointing-left.png" alt="arrow-pointing-left"/>
            <a :href="route('dashboard')" class="ml-3">Go to Dashboard</a>
        </div>
    </div>
</template>

<style scoped>

li{
    margin-top: 10px;
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


.top{
    width: 500px;
    height: 100px;
    border-bottom: 1px solid #e8e5e5;
    margin-top: 20px;
    margin-bottom: 10px;
    padding-bottom: 30px;
}

.friendlist{
    margin-bottom: 100px ;
    overflow-y: auto;
}

.btn{
    border-radius: 5px;
    padding: 7px 10px;
    background-color: #333;
    color: #f5e9e9;
    width: max-content;
    height: max-content;
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

.image:hover{
    cursor: pointer;
}

.rounded-full {
    border-radius: 9999px; /* 用一个非常大的值，使其看起来是圆形 */
}

.friendbt{
    border-bottom: 1px solid #ececec;
    padding: 8px 10px;
    width: 400px;
}

.friendbt:hover {
    transition: 0.5s;
    background-color: #f0f0f0; /* 替换为你想要的颜色 */
    cursor: pointer;
}

.bt-line{
    margin-bottom: 3px;
}

.last-msg{
    color: #828282;
    font-size: 14px;
}

.text{
    font-size: 17px;
}
</style>
