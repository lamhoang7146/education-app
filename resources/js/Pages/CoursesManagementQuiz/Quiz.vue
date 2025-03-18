<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import Modal from "../../Components/Modal.vue";
import {ref, watch} from 'vue'
import InputField from "../../Components/InputField.vue";
import {Switch} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import MessageSession from "../../Components/MessageSession.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";

const props = defineProps({
    quizzes: Object,
    message: String,
    status: Boolean,
})
const statusAddQuiz = ref(false)
watch(statusAddQuiz, (newStatus) => {
    formAddQuiz.status = newStatus;
});

const isOpenAddQuiz = ref(false)
const openAddQuiz = () => {
    isOpenAddQuiz.value = true;
}
const closeAddQuiz = () => {
    isOpenAddQuiz.value = false;
}


const formAddQuiz = useForm({
    name: null,
    status: false,
})

const handleAddVideo = () => {
    formAddQuiz.post(route('courses.management.quiz.add'), {
        forceFormData: true,
        onSuccess: () => {
            formAddQuiz.reset();
            isOpenAddQuiz.value = false;
        }
    });
}

const statusEditQuiz = ref(false)
watch(statusEditQuiz, (newStatus) => {
    formEditQuiz.status = newStatus;
});
const formEditQuiz = useForm({
    id: null,
    name: null,
    status: statusEditQuiz.value
})
let isEditQuiz = ref(false)
const closeEditQuiz = () => {
    isEditQuiz.value = false;
    formEditQuiz.reset();
}
const openEditQuiz = id => {
    isEditQuiz.value = true;
    const category = props.quizzes.data.find(item => item.id === parseInt(id));
    formEditQuiz.id = category.id
    formEditQuiz.name = category.name;
    statusEditQuiz.value = category.status;
}

const handleUpdateQuiz = () => {
    formEditQuiz.post(route('courses.management.quiz.update', {quiz: formEditQuiz.id}), {
        onSuccess: () => {
            isEditQuiz.value = false;
            formEditQuiz.reset('name', 'id');
        }
    })
}
</script>
<template>
    <Modal :is-open="isOpenAddQuiz" @close="closeAddQuiz">
        <Container @click.stop class="w-[500px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Create quiz</h1>
            <form @submit.prevent="handleAddVideo" class="grid space-y-4 overflow-x-hidden">
                <InputField
                    placeholder="Enter quiz name"
                    label="Quiz name"
                    v-model="formAddQuiz.name"
                    :error="formAddQuiz.errors.name"
                />
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This quiz will be {{ statusAddQuiz ? 'visible' : 'hidden' }} to users.</p>
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
                    <span @click="isOpenAddQuiz = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button type="submit" :disabled="formAddQuiz.processing" class="px-3 text-sm">Add
                        Quiz
                    </Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Modal :is-open="isEditQuiz" @close="closeEditQuiz">
        <Container @click.stop class="w-[500px]">
            <h1 class="text-xl font-medium mb-2">Edit category</h1>
            <form class="space-y-6" @submit.prevent="handleUpdateQuiz">
                <div>
                    <InputField
                        placeholder="Enter category name"
                        label="Category name"
                        type="text"
                        v-model="formEditQuiz.name"
                        :error="formEditQuiz.errors.name"
                    />
                    <p class="text-sm mt-2">This is the name will be displayed for this category</p>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This category will be {{ statusEditQuiz ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusEditQuiz"
                                :class="statusEditQuiz ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusEditQuiz ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="isEditQuiz = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button :disabled="formEditQuiz.processing" class="px-3 text-sm">Update Category</Button>
                </div>
            </form>
        </Container>
    </Modal>

    <Container class="flex justify-between items-center">
        <div class="flex items-center gap-x-4">
            <div>
                <h1 class="font-medium text-xl">Quiz</h1>
                <p class="text-base">Manage your quiz</p>
            </div>
        </div>
        <div>
            <Button @click="openAddQuiz" class="!py-[5px] px-3"><span><i
                class="fa-solid fa-plus text-xs -translate-y-[2px] mr-2"></i></span> Add quiz
            </Button>
        </div>
    </Container>
    <MessageSession
        class="my-6"
        :message="message"
        :status="status"
    />
    <div class="grid grid-cols-3 gap-x-5 gap-y-5">
        <Container v-for="quiz in quizzes.data">
            <div class="flex items-center justify-between">
                <h1 class="font-medium line-clamp-1">{{ quiz.name }}</h1>
                <div class="text-sm">
                    <span class="pt-[3px] pb-[5px] px-3 rounded-md bg-green-100 text-green-500"
                          v-if="quiz.status">Active</span>
                    <span class="pt-[3px] pb-[5px] px-3 rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
                </div>
            </div>
            <div class="my-4 border-t-[.1px]"></div>
            <div class="flex justify-between items-center gap-x-4 mt-3">
                <Link class="text-[#8278F2] hover:underline text-sm"
                      :href="route('courses.management.quiz.detail',{quiz:quiz.id})">View detail
                </Link>
                <div @click="openEditQuiz(quiz.id)"
                     class="hover-selected dark:dark-hover-selected font-medium px-3 py-1 rounded-md text-sm cursor-pointer"><span><i
                    class="fa-solid fa-pen-to-square text-sm mr-1"></i></span> Edit
                </div>
            </div>
        </Container>
    </div>
    <PaginationLinks class="mt-6" :paginator="quizzes"></PaginationLinks>
</template>
