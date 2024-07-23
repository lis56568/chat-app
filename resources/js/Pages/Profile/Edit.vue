<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import {ref} from "vue";
import axios from "axios";

// defineProps({
//     mustVerifyEmail: {
//         type: Boolean,
//     },
//     status: {
//         type: String,
//     },
//     result:{
//         type: String,
//     },
//     csrf_token: String
// });

const prop = defineProps(['mustVerifyEmail', 'status', 'result', 'csrf_token']);



const imageUrl = ref('');
const selectedFile = ref(null);
const res = ref(false);

const handleFileUpload = (event) => {
    selectedFile.value = event.target.files[0];
};

const uploadImage = async () => {
    const formData = new FormData();
    formData.append('image', selectedFile.value);

    const response = await axios.post('/profile', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': prop.csrf_token
        }})
        .then(
        response => {
            imageUrl.value = response.data.path;
            res.value = true;
            setTimeout(()=>{
                res.value = false;
            }, 3000)
        })
        .catch(error => {
        console.error('Error uploading image:', error);
    });
};


</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Profile Avatar</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update your account's profile avatar.
                        </p>
                    </header>
                    <div class="mt-6 ">
                        <form @submit.prevent="uploadImage" class="space-y-6">
                            <div class="flex">
                                <input type="file" @change="handleFileUpload">
                                <img v-if="imageUrl" :src="imageUrl" alt="Uploaded Image">
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Save
                                </button>
                                <transition name="fade">
                                    <p v-if="res" class="text-sm text-gray-600">Saved.</p>
                                </transition>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="prop.mustVerifyEmail"
                        :status="prop.status"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
