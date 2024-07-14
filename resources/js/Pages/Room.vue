<script setup>
import {computed, ref } from "vue";
const { friend, id, csrfToken } = defineProps(
    [
        'friend',
        'id',
        'csrfToken'
    ]
)
const messages = ref([]);
const newMessage = ref('');
const messageContainer = ref(null);

const num1 = computed(() => Math.min(id, friend[0]['id']));
const num2 = computed(() => Math.max(id, friend[0]['id']));
const channel_name = computed(() => `chat.${num1.value}.${num2.value}`);

const channel = Echo.private(channel_name.value)
    .listen('MessageSent',(e) => {
        console.log('websocket connection !');
        console.log(e);
        messages.value.push({
            message: e.message,
            sender: e.sender,
            receiver: e.receiver
        });
    });
const sendMessage = () => {
    axios.post('/friend/send', {
        sender_id: id,
        receiver_id: friend[0]['id'],
        message: newMessage.value
    }).then( () => {
        newMessage.value = '';
    }).catch(error => {
        console.error(error);
    });
};
</script>

<template>
    <div class="bg-gray-300">
        <div class="chat" >
            <div class="top">
                <img width="96" height="96" src="https://img.icons8.com/wired/64/7950F2/chat.png" alt="chat"/>
                <span>{{ friend[0]['name'] }}</span>
            </div>

            <div class="message-container flex flex-col-reverse">
                <div class="messages" v-for="msg in messages.slice().reverse()">
                    <div v-if="msg['sender']['id'] === id" >
                        <div class="right message">
                            <p>{{ msg.message }}</p>
                        </div>
                    </div>
                    <div v-else class="left message">
                        <img width="48" height="48" src="https://img.icons8.com/color/48/user-male-circle--v1.png" alt="Profile picture" style="margin-right: 10px"/>
                        <p>{{ msg.message }}</p>
                    </div>
                </div>
            </div>

            <div class="bottom">
                <input type="text" id="message" v-model="newMessage" placeholder="type something to here...." @keyup.enter="sendMessage" >
                <button @click="sendMessage">
                    <img width="20" height="20" src="https://img.icons8.com/material-rounded/24/7950F2/sent.png" alt="sent"/>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.bg-gray-300 {
    background-color: #f0f4fd;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

a {
    color: #7950f2;
}

.chat {
    max-height: 600px;
    max-width: 700px;
    width: 100%;
    background-color: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    display: flex;
    flex-direction: column;
}

.top {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    padding: 20px;
    border-bottom: 1px solid #ccc;

}

.messages {
    padding: 1px 0px 10px 0px;
}

.bottom {
    display: flex;
    align-items: center;
    padding: 10px;
    border-top: 1px solid #ccc;
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
    padding: 7px 12px;
    border-radius: 18px;
    font-size: .9375rem;
    overflow-wrap: anywhere;
    max-width: 200px;
}

.right.message > p
{
    color: white;
    background-color: #6b19ff;
}

.left.message > p
{
    color: white;
    background-color: #2f80ed;
}

.left.message, .right.message{
    display:flex;
    align-items: center;
    height: auto;

}
.left.message{
    justify-content: flex-start;
    margin-left: 20px;
}

.right.message{
    justify-content: flex-end;
    margin-right: 20px;
}
.message-container{
    height: 300px;
    max-height: 300px;
    overflow-y: auto;
}

</style>
