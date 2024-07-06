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
    <div class="flex flex-col items-center justify-center h-screen bg-gray-200">
        <div class="container">
            <div class="notice" v-if="props.notice">
                {{ props.notice }}
            </div>
            <div class="top">
                <img width="64" class="logo" height="64" src="https://img.icons8.com/pastel-glyph/64/333333/communication--v2.png" alt="communication--v2"/>
                <button class="logout">登出</button>
            </div>

            <div class="mid">
                <form method="POST" action="/friend/store">
                    <div class="boarder">
    <!--                        <label>輸入好友代碼</label>-->
                        <input type="text" name="friend_id" class="addID" placeholder="輸入好友代碼">
                        <input type="hidden" v-model="props.csrfToken" name="_token">
                        <button class="btn" type="submit">加入好友</button>
                    </div>
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
    align-items: center;
    justify-content: center;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.container{
    padding: 30px 10px;
    border-radius: 15px;
    height: 600px;
    width: 500px;
}

label{
    margin-right: 20px;
    color: #521fec;
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


.addIDArea{
    background-color: #ccc0f3;
    padding: 5px 8px;
    border-radius: 10px;
    width: max-content;
}

.top{
    width: 500px;
    height: 100px;
    border-bottom: 1px solid #e8e5e5;
    margin-bottom: 20px;
    margin-top: 10px;
    display: flex;
    align-items: center;
    padding-bottom: 30px;
}

.mid{
    margin-top: 40px;
    height: 500px;
}

.logout{
    border-radius: 5px;
    padding: 5px 10px;
    background-color: #333;
    color: #f5e9e9;
    width: max-content;
    height: max-content;
    margin-left: 30px;

}
.logo{
    margin-left: 60px;
    margin-right: 250px;
}

</style>
