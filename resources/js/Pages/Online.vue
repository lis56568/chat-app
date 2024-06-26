<script setup>
const { users , user_id } = defineProps([
    'users',
    'user_id'
])
console.log(users);
</script>

<template>
    <div v-if="users.length > 0">
        <h3>平台上的使用者</h3>
        <ul>
            <li v-for="user in users" :key="user['id']">
                {{ user['name'] }}
                <div>好友代碼#{{ user['id'] }}</div>
            </li>
        </ul>
    </div>
    <div v-else>平台上無其他使用者</div>
</template>
