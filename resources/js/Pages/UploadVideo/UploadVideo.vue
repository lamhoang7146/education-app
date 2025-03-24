<!-- filepath: c:\Xampp\htdocs\education-app\resources\js\Pages\UploadVideo\UploadVideo.vue -->

<script setup>
import Container from "../../Components/Container.vue";
import MessageSession from "../../Components/MessageSession.vue";
import Modal from "../../Components/Modal.vue";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";

const props = defineProps({
    message: String,
    status: Boolean,
    videoId: String,
});

let isModalOpen = ref(false);
const closeModal = () => {
    isModalOpen.value = false;
};
const openModal = () => {
    isModalOpen.value = true;
};

const form = useForm({
    title: '',
    description: '',
    video: null,
});

const handleFileUpload = (event) => {
    form.video = event.target.files[0];
};

const submit = () => {
    form.post(route('api.upload-video'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <Modal :isOpen="isModalOpen" @close="closeModal">
        <div @click.stop class="w-[900px] h-[90%] py-16 main px-16 bg-content dark:dark-bg-content rounded-md box-shadow-copy overflow-auto">
            <h1 class="text-center font-medium text-2xl">Upload Video</h1>
            <p class="text-center mt-2 opacity-90">Upload your video to YouTube</p>
            <form @submit.prevent="submit">
                <InputField
                    label="Title"
                    placeholder="Enter Video Title"
                    type="text"
                    v-model="form.title"
                    :error="form.errors.title"
                />
                <InputField
                    label="Description"
                    placeholder="Enter Video Description"
                    type="textarea"
                    v-model="form.description"
                    :error="form.errors.description"
                />
                <div class="mt-4">
                    <label for="video" class="block font-medium">Video</label>
                    <input type="file" @change="handleFileUpload" accept="video/*" required class="mt-2">
                    <span v-if="form.errors.video" class="text-red-500 text-sm">{{ form.errors.video }}</span>
                </div>
                <div class="flex justify-center mt-6">
                    <Button :disabled="form.processing" class="px-4 !py-[6px]">Upload</Button>
                </div>
            </form>
        </div>
    </Modal>
    <div class="flex justify-center mt-10">
        <Button @click="openModal" class="px-4 !py-[6px]">Upload New Video</Button>
    </div>
    <MessageSession
        class="mt-5"
        :status="status"
        :message="message"
    />
    <div v-if="videoId" class="mt-5 text-center">
        <p>Video uploaded successfully! Video ID: {{ videoId }}</p>
        <a :href="'https://www.youtube.com/watch?v=' + videoId" target="_blank" class="text-blue-500">Watch on YouTube</a>
    </div>
</template>
