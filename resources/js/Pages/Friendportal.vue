<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Chat" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Real-Time Chat APP</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Chat now !!</div>
                    <form action="/friend" method="get">
                        <div class="btn">
                            <button type="submit">
                                Go to Chat Room
                            </button>
                            <img width="30" height="30" src="https://img.icons8.com/carbon-copy/100/FFFFFF/chat.png" alt="chat"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.bg-white{
    display: flex;
    align-items: center;
}
button {
    margin-right: 5px;
    color: white;
}

.btn {
    display: flex;
    background-color: black;
    padding: 3px 15px;
    border-radius: 15px;
}

.btn:hover {
    background-color: #333;
    transition: 0.5s;
}

.btn:hover img {
    filter: brightness(0) invert(1);
    cursor:pointer;
}
</style>
