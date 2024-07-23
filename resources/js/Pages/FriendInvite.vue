<script setup>
import { usePage } from '@inertiajs/vue3'
const page = usePage()
const { senders, user } = defineProps(['senders', 'user'])

</script>
<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-200">
        <div class="flex flex-col justify-center items-center container">
            <div class="top flex justify-center">
                <img width="64" height="64" src="https://img.icons8.com/pastel-glyph/64/333333/communication--v2.png" alt="communication--v2"/>
                <div class="flex mr-2 ml-5">
                    <div>
                        <span class="text-2xl">{{ user['name'] }} # {{ user['id'] }}</span>
                        <div class="flex flex-row items-center">
                            <div class="status-dot"></div>
                            <span class="dark:text-gray-500">Online</span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="senders.length > 0">
                <ul class="mt-10">
                    <li v-for="sender in senders">
                        <div id="sender" class="flex items-center">
                            <div class="ml-4">
                                {{ sender['name'] }}
                            </div>
                            <div class="ml-20 btn">
                                <form method="post" action="/friend/accept">
                                    <input type="hidden" :value="sender['id']" name="friend_id">
                                    <input type="hidden" :value="page.props.csrfToken" name="_token" />
                                    <button type="submit">接受</button>
                                </form>
                            </div>
                            <div class="ml-3 btn">
                                <form method="post" action="/friend/cancel">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="friend_id" :value="sender['id']">
                                    <input type="hidden" :value="page.props.csrfToken" name="_token" />
                                    <button type="submit">忽略</button>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else class="mt-10">尚未有好友寄送邀請給您！</div>

        </div>
        <div class="flex justify-start mt-5 items-center">
            <img width="24" height="24" src="https://img.icons8.com/material-sharp/24/333333/arrow-pointing-left.png" alt="arrow-pointing-left"/>
            <a :href="route('home')" class="ml-3">Back</a>
        </div>
    </div>
</template>
<style scoped>
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

.top{
    width: 500px;
    height: 100px;
    border-bottom: 1px solid #e8e5e5;
    margin-top: 30px;
    margin-bottom: 10px;
    padding-bottom: 30px;
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 5px;
    background-color: green;
}

.btn{
    padding: 7px 10px;
    border: 1px solid black;
    background-color: #333333;
    color: white;
    border-radius: 7px;
    width: max-content;
    height: max-content;
}
</style>
