<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import Modal from "../../Components/Modal.vue";
import InputField from "../../Components/InputField.vue";
import {vAutoAnimate} from '@formkit/auto-animate';
import MessageSession from "../../Components/MessageSession.vue";

const props = defineProps({
    quiz: Object,
    quiz_content_details: Object,
    message: String,
    status: Boolean,
})

const isOpenAddQuizDetail = ref(false)
const openAddQuizDetail = () => {
    isOpenAddQuizDetail.value = true;
}
const closeAddQuizDetail = () => {
    isOpenAddQuizDetail.value = false;
}


const formAddQuizDetail = useForm({
    question: null,
    answers: null,
    result: null,
    quiz_id: props.quiz.id,
})

const handleAddQuizDetail = () => {
    formAddQuizDetail.post(route('courses.management.quiz.detail.add'), {
        onSuccess: () => {
            formAddQuizDetail.reset();
            isOpenAddQuizDetail.value = false;
        },
        preserveScroll: true,
    });
}
// Edit
const isOpenEditQuizDetail = ref(false)
const formEditQuizDetail = useForm({
    id: null,
    question: null,
    answers: null,
    result: null,
    quiz_id: props.quiz.id,
})
const openEditQuizDetail = id => {
    isOpenEditQuizDetail.value = true;
    const item = props.quiz_content_details.find(item => item.id === id);
    formEditQuizDetail.id = item.id
    formEditQuizDetail.question = item.question;
    formEditQuizDetail.answers = item.answers;
    formEditQuizDetail.result = item.result;
}
const closeEditQuizDetail = () => {
    isOpenEditQuizDetail.value = false;
}
const handleEditQuizDetail = () => {
    formEditQuizDetail.put(route('courses.management.quiz.detail.update', {id: formEditQuizDetail.id}), {
        onSuccess: () => {
            formEditQuizDetail.reset();
            isOpenEditQuizDetail.value = false;
        },
        preserveScroll: true,
    });
};
// Delete
const isDelete = ref(false)
const questionName = ref(null)
const formDeleteQuiz = useForm({
    id:null
})
const openIsDelete = id => {
    isDelete.value = true
    const item = props.quiz_content_details.find(item => item.id === id);
    questionName.value = item.question;
    formDeleteQuiz.id = item.id
}
const closeIsDelete = () => {
    isDelete.value = false
    questionName.value = null
    formDeleteQuiz.id = null
}
const handleDeleteQuiz = ()=>{
    formDeleteQuiz.delete(route('courses.management.quiz.detail.delete',{id:formDeleteQuiz.id}), {
        preserveScroll: true,
        onSuccess:()=>{
            formDeleteQuiz.reset();
            isDelete.value = false
        }
    })
}
</script>
<template>
    <Modal :is-open="isOpenAddQuizDetail" @close="closeAddQuizDetail">
        <Container @click.stop class="w-[500px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Create quiz</h1>
            <form @submit.prevent="handleAddQuizDetail" class="grid space-y-4 overflow-x-hidden">
                <InputField
                    placeholder="Enter quiz name"
                    label="Quiz name"
                    v-model="formAddQuizDetail.question"
                    :error="formAddQuizDetail.errors.question"
                />
                <div>
                    <label class="text-sm block mb-1 cursor-pointer font-normal" for="answersAdd">Answers</label>
                    <textarea
                        v-model="formAddQuizDetail.answers"
                        rows="4"
                        placeholder="Enter quiz answers with comma"
                        id="answersAdd"
                        class="resize-none disabled:opacity-70 w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"></textarea>
                    <div v-auto-animate>
                        <p v-if="!!formAddQuizDetail.errors.answers" class="text-red-400 mt-1 text-sm">
                            {{ formAddQuizDetail.errors.answers }}</p>
                    </div>
                </div>

                <InputField
                    placeholder="Enter result include answers"
                    label="Quiz result"
                    v-model="formAddQuizDetail.result"
                    :error="formAddQuizDetail.errors.result"
                />
                <div class="flex justify-end gap-x-4">
                    <span @click="isOpenAddQuizDetail = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button type="submit" :disabled="formAddQuizDetail.processing" class="px-3 text-sm">Add
                        Question
                    </Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Modal :is-open="isOpenEditQuizDetail" @close="closeEditQuizDetail">

        <Container @click.stop class="w-[500px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Edit quiz</h1>
            <form @submit.prevent="handleEditQuizDetail" class="grid space-y-4 overflow-x-hidden">
                <InputField
                    placeholder="Enter quiz name"
                    label="Quiz name"
                    v-model="formEditQuizDetail.question"
                    :error="formEditQuizDetail.errors.question"
                />
                <div>
                    <label class="text-sm block mb-1 cursor-pointer font-normal" for="answersEdit">Answers</label>
                    <textarea
                        v-model="formEditQuizDetail.answers"
                        rows="4"
                        placeholder="Enter quiz answers with comma"
                        id="answersEdit"
                        class="resize-none disabled:opacity-70 w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"></textarea>
                    <div v-auto-animate>
                        <p v-if="!!formEditQuizDetail.errors.answers" class="text-red-400 mt-1 text-sm">
                            {{ formEditQuizDetail.errors.answers }}</p>
                    </div>
                </div>

                <InputField
                    placeholder="Enter result include answers"
                    label="Quiz result"
                    v-model="formEditQuizDetail.result"
                    :error="formEditQuizDetail.errors.result"
                />
                <div class="flex justify-end gap-x-4">
                    <span @click="isOpenEditQuizDetail = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button type="submit" :disabled="formEditQuizDetail.processing" class="px-3 text-sm">Update
                        Question
                    </Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Modal :is-open="isDelete" @close="closeIsDelete">
        <Container @click.stop class="max-w-[500px]">
            <h1 class="text-lg font-medium">Are you sure you want to delete question: {{ questionName }}</h1>
            <p class="text-sm mt-2 mb-4">Please confirm your action. This will delete question: <span
                class="font-medium">{{ questionName }}</span></p>
            <div class="space-x-2">
                <button
                    @click="handleDeleteQuiz"
                    class="text-center bg-[#7367F0] text-sm text-white py-2 px-4 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition">
                    Confirm
                </button>
                <button
                    @click="closeIsDelete"
                    class="text-center bg-red-500 text-sm text-white py-2 px-4 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition">
                    Cancel
                </button>
            </div>
        </Container>
    </Modal>

    <Container class="flex justify-between items-center">
        <div class="flex items-center gap-x-4">
            <div>
                <Link :href="route('courses.management.quiz')"
                      class="text-base underline underline-offset-[2px] text-[#8278F2]"><i
                    class="fa-solid fa-arrow-left text-sm"></i> <span>Leave</span></Link>

                <h1 class="font-medium text-lg mt-1">Quiz name: {{ quiz.name }}</h1>
            </div>
        </div>
        <div>
            <Button @click="openAddQuizDetail" class="!py-[5px] px-3"><span><i
                class="fa-solid fa-plus text-xs -translate-y-[2px] mr-2"></i></span> Add question
            </Button>
        </div>
    </Container>
    <MessageSession
        class="my-6"
        :message="message"
        :status="status"
    />
    <Container class="space-y-6">
        <div v-if="quiz_content_details.length > 0" class="p-6 bg-content dark:dark-bg-content rounded-md shadow-md"
             v-for="(quiz_content_detail,index) in quiz_content_details">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-medium">Q{{ index + 1 }}: {{ quiz_content_detail.question }}</h1>
                </div>
                <div class="flex items-center gap-x-2">
                    <div class="cursor-pointer" @click="openEditQuizDetail(quiz_content_detail.id)">
                        <i class="fa-solid fa-pen-to-square text-green-500"></i>
                    </div>
                    <div class="cursor-pointer" @click="openIsDelete(quiz_content_detail.id)">
                        <i class="fa-solid fa-trash-can text-red-400"></i>
                    </div>
                </div>
            </div>
            <div>
                <h1 class="font-medium mt-1 text-gray-400">Answer</h1>
                <p>{{ quiz_content_detail.result }}</p>
            </div>
        </div>
        <div v-else>
            <h1 class="text-center">There are not question</h1>
        </div>
    </Container>

</template>
