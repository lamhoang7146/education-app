<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import Modal from "../../Components/Modal.vue";
import {ref, watch} from 'vue'
import InputField from "../../Components/InputField.vue";
import Tiptap from "../../Components/Tiptap.vue";
import {Switch} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";

const statusAddVideo = ref(false)
watch(statusAddVideo, (newStatus) => {
    formAddVideo.status = newStatus;
});

const isOpenAddVideo = ref(false)
const openAddVideo = () => {
    isOpenAddVideo.value = true;
}
const closeAddVideo = () => {
    isOpenAddVideo.value = false;
}

// Thêm biến để hiển thị tên file đã chọn
const selectedFileName = ref('');

const formAddVideo = useForm({
    title: null,
    description: null,
    video: null,
    status: false,
})

const handleAddVideo = () => {
    // Sử dụng form đa phần (multipart) để tải lên file
    formAddVideo.post(route('courses.management.video.add'), {
        forceFormData: true,
        onSuccess: () => {
            formAddVideo.reset();
            selectedFileName.value = '';
            isOpenAddVideo.value = false;
        }
    });
}

// Thêm hàm xử lý tải lên video
const uploadVideo = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Kiểm tra định dạng file
        if (file.type !== 'video/mp4') {
            alert('Chỉ chấp nhận file video MP4.');
            return;
        }

        // Giới hạn kích thước file (100MB)
        const maxSize = 100 * 1024 * 1024; // 100MB in bytes
        if (file.size > maxSize) {
            alert('Kích thước file không được vượt quá 100MB.');
            return;
        }

        // Lưu file vào form
        formAddVideo.video = file;
        selectedFileName.value = file.name;
    }
}
</script>
<template>
    <Modal :is-open="isOpenAddVideo" @close="closeAddVideo">
        <Container @click.stop class="w-[600px] h-[650px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Create video</h1>
            <form @submit.prevent="handleAddVideo" class="grid space-y-4 overflow-x-hidden">
                <label class="text-sm block mb-1 cursor-pointer font-normal" for="videoAddFile">Video file
                    <div class="border-[1px] border-gray-200 h-12 mt-2 rounded-md flex items-center justify-center">
                        <div class="flex items-center justify-center gap-x-2">
                            <i class="fa-solid fa-upload"></i>
                            <p class="font-medium">{{ selectedFileName || 'Select video file' }}</p>
                        </div>
                    </div>
                </label>
                <input @change="uploadVideo" hidden id="videoAddFile" type="file" accept="video/mp4">
                <InputField
                    placeholder="Enter video title"
                    label="Video title"
                    v-model="formAddVideo.title"
                    :error="formAddVideo.errors.title"
                />
                <label class="text-sm block mb-1 cursor-pointer font-normal" for="descriptionAddCategory">Description</label>
                <Tiptap :error="formAddVideo.errors.description" v-model="formAddVideo.description" />
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This video will be {{ statusAddVideo ? 'visible' : 'hidden' }} to users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusAddVideo"
                                :class="statusAddVideo ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusAddVideo ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="isOpenAddVideo = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button type="submit" :disabled="formAddVideo.processing || !formAddVideo.video" class="px-3 text-sm">Add
                        Video
                    </Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Container class="flex justify-between items-center">
        <div class="flex items-center gap-x-4">
            <div>
                <h1 class="font-medium text-xl">Video</h1>
                <p class="text-base">Manage your videos</p>
            </div>
        </div>
        <div>
            <Button @click="openAddVideo" class="!py-[5px] px-3"><span><i
                class="fa-solid fa-plus text-xs -translate-y-[2px] mr-2"></i></span> Add video
            </Button>
        </div>
    </Container>
</template>
