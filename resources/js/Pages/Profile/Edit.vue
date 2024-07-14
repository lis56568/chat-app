<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import AddUserAvatar from "@/Pages/Profile/Partials/AddUserAvatar.vue";
import { Head } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref} from "vue";
import axios from "axios";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    path:{
        type: String,
    },
    csrf_token: String
});

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
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
<!--                    <AddUserAvatar :path="path" />-->
<!--                        {{ path }}-->

                    <header>
                        <h2 class="text-lg font-medium text-gray-900">Profile Avatar</h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Update your account's profile avatar.
                        </p>
                    </header>
                    <div class="mt-6 ">
                        <form method="post" action="/upload" class="space-y-6">
                            <div class="flex">
                                <input type="file" @change="handleFileUpload">
                                <img v-if="imageUrl" :src="imageUrl" alt="Uploaded Image">
                            </div>
                            <div v-if="path">
                                <img :src="path" alt="User Avatar">
                            </div>

                            <div class="flex items-center gap-4">
                                <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
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
