<script setup>
import { router, usePage } from '@inertiajs/vue3'
const page = usePage()
const { senders } = defineProps(['senders'])

</script>
<template>
    <h3>好友邀請列表</h3>
    <div v-if="senders.length > 0">
        <ul>
            <li v-for="sender in senders">
                <div id="sender">
                    {{ sender['name'] }}
                    <form method="post" action="/friend/accept">
                        <input type="hidden" :value="sender['id']" name="friend_id">
                        <input type="hidden" :value="page.props.csrfToken" name="_token" />
                        <button type="submit">接受</button>
                    </form>
                    <form method="post" action="/friend/cancel">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="friend_id" :value="sender['id']">
                        <input type="hidden" :value="page.props.csrfToken" name="_token" />
                        <button type="submit">忽略</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <div v-else>尚未有好友寄送邀請給您！</div>
</template>
<style scoped>

</style>
