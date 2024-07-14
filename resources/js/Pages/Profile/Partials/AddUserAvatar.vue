<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const { path } = defineProps([
    'path'
])

const user = usePage().props.auth.user;

const imageUrl = ref('');
const selectedFile = ref(null);

const handleFileUpload = (event) => {
    selectedFile.value = event.target.files[0];
};

const uploadImage = async () => {
    const formData = new FormData();
    formData.append('image', selectedFile.value);

    try {
        const response = await axios.post('/profile', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        imageUrl.value = response.data.path;
    } catch (error) {
        console.error('Error uploading image:', error);
    }
};


</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Avatar</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile avatar.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div class="flex">
                <input type="file" @change="handleFileUpload">
                <img v-if="imageUrl" :src="imageUrl" alt="Uploaded Image">
            </div>
            <div v-if="path">
                <img :src="path" alt="User Avatar">
            </div>
            <form>
                <div class="flex items-center gap-4">
                    <PrimaryButton @click="uploadImage">Save</PrimaryButton>
                </div>
            </form>
        </div>

    </section>
</template>
