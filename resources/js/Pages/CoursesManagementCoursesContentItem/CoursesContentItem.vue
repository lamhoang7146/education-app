<script setup>
import Container from "../../Components/Container.vue";
import {Switch, Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {vAutoAnimate} from "@formkit/auto-animate";
import InputField from "../../Components/InputField.vue";
import {ref, watch} from "vue";
import {useForm} from "@inertiajs/vue3";
import Button from "../../Components/Button.vue";
import {route} from "ziggy-js";

const props = defineProps({
    courses_id: Number,
    content_item_id: Number
})
const types = [
    {
        type: "quiz",
        id: 1
    },
    {
        type: "video",
        id: 2
    }
];
const statusAddQuiz = ref(false);
watch(statusAddQuiz, (value) => {
    formAddQuiz.status = value
})
const formAddQuiz = useForm({
    name: null,
    status: statusAddQuiz.value
})
const handleAddQuiz = () => {
    formAddQuiz.post(route('courses.management.quiz.add', {
        courses_id: props.courses_id,
        content_item_id: props.content_item_id
    }), {
        onSuccess: () => {
            formAddQuiz.reset()
        }
    })
}

// Video upload form state and logic
const statusAddVideo = ref(false);
const videoFile = ref(null);

watch(statusAddVideo, (value) => {
    formAddVideo.status = value
})

const formAddVideo = useForm({
    title: null,
    description: null,
    status: statusAddVideo.value,
    file: null
})

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        videoFile.value = file;
        formAddVideo.file = file;
    }
}

const handleAddVideo = () => {
    formAddVideo.post(route('courses.management.video.add', {
        courses_id: props.courses_id,
        content_item_id: props.content_item_id
    }), {
        onSuccess: () => {
            formAddVideo.reset();
            videoFile.value = null;
        }
    });
}
</script>
<template>
    <Container>
        <h1 class="text-lg font-medium">Add new content item</h1>
    </Container>
    <Container class="mt-6">
        <TabGroup>
            <TabList class="flex items-center gap-x-2 border-b-[1px] border-gray-200 pb-4">
                <Tab v-for="type in types" v-slot="{ selected }">
                    <div
                        :class="['p-2 bg-[#7367F0] text-white rounded-md text-sm font-medium',selected ? 'bg-opacity-100' : 'bg-opacity-20 !text-[#7367F0]']">
                        {{ type.type }}
                    </div>
                </Tab>
            </TabList>
            <tab-panels class="max-h-[500px] overflow-y-auto main mt-4 overflow-x-hidden">
                <div v-auto-animate>
                    <tab-panel>
                        <h1 class="text-lg font-medium mb-2">Add quiz</h1>
                        <form @submit.prevent="handleAddQuiz" class="w-1/2 grid gap-y-4">
                            <InputField
                                v-model="formAddQuiz.name"
                                label="Name quiz"
                                type="text"
                                placeholder="Name quiz"
                                :error="formAddQuiz.errors.name"
                            />
                            <div
                                class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                                <div>
                                    <h1 class="font-medium">Active Status</h1>
                                    <p class="text-sm">This quiz will be {{ statusAddQuiz ? 'visible' : 'hidden' }} to
                                        users.</p>
                                </div>
                                <div>
                                    <div>
                                        <Switch
                                            v-model="statusAddQuiz"
                                            :class="statusAddQuiz ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                            class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                                        >
                                            <span class="sr-only">Use setting</span>
                                            <span
                                                aria-hidden="true"
                                                :class="statusAddQuiz ? 'translate-x-6' : 'translate-x-0'"
                                                class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                            />
                                        </Switch>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-x-4">
                                <Link :href="route('courses.management.courses.content',{id:courses_id})"
                                      class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">
                                    Cancel
                                </Link>
                                <Button :disabled="formAddQuiz.processing" class="px-3 text-sm">Create Quiz</Button>
                            </div>
                        </form>
                    </tab-panel>
                </div>
                <div v-auto-animate>
                    <tab-panel>
                        <h1 class="text-lg font-medium mb-2">Add video</h1>
                        <form @submit.prevent="handleAddVideo" class="w-1/2 grid gap-y-4">
                            <InputField
                                v-model="formAddVideo.title"
                                label="Video Title"
                                type="text"
                                placeholder="Enter video title"
                                :error="formAddVideo.errors.title"
                            />
                            <div class="grid gap-y-1">
                                <label class="text-sm font-medium">Description</label>
                                <textarea
                                    v-model="formAddVideo.description"
                                    class="border-[1px] border-slate-300 rounded-md p-2 text-sm"
                                    placeholder="Enter video description"
                                    rows="3"
                                ></textarea>
                                <p v-if="formAddVideo.errors.description" class="text-red-500 text-xs mt-1">
                                    {{ formAddVideo.errors.description }}
                                </p>
                            </div>
                            <div class="grid gap-y-1">
                                <label class="text-sm font-medium">Video File</label>
                                <input
                                    type="file"
                                    @change="onFileChange"
                                    accept="video/mp4,video/mov,video/avi,video/wmv"
                                    class="border-[1px] border-slate-300 rounded-md p-2 text-sm"
                                />
                                <p v-if="formAddVideo.errors.file" class="text-red-500 text-xs mt-1">
                                    {{ formAddVideo.errors.file }}
                                </p>
                            </div>
                            <div
                                class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                                <div>
                                    <h1 class="font-medium">Active Status</h1>
                                    <p class="text-sm">This video will be {{ statusAddVideo ? 'visible' : 'hidden' }} to
                                        users.</p>
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
                                <Link :href="route('courses.management.courses.content',{id:courses_id})"
                                      class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">
                                    Cancel
                                </Link>
                                <Button :disabled="formAddVideo.processing" class="px-3 text-sm">Upload Video</Button>
                            </div>
                        </form>
                    </tab-panel>
                </div>
            </tab-panels>
        </TabGroup>
    </Container>
</template>
