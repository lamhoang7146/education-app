<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import {ref, reactive} from "vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import Modal from "../../Components/Modal.vue";
import InputField from "../../Components/InputField.vue";
import {vAutoAnimate} from '@formkit/auto-animate';
import MessageSession from "../../Components/MessageSession.vue";
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import {generateQuizQuestions} from "@/AiModel.js";

const props = defineProps({
    quiz: Object,
    quiz_content_details: Object,
    message: String,
    status: Boolean,
})

// Add Quiz Modal
const isOpenAddQuizDetail = ref(false)
const openAddQuizDetail = () => {
    isOpenAddQuizDetail.value = true;
    // Initialize with 2 answers
    formAddQuizAnswers.value = [
        { text: '', id: 1 },
        { text: '', id: 2 }
    ];
    selectedCorrectAnswer.value = null;
}
const closeAddQuizDetail = () => {
    isOpenAddQuizDetail.value = false;
}

// Form for adding quiz questions
const formAddQuizAnswers = ref([
    { text: '', id: 1 },
    { text: '', id: 2 }
]);
const selectedCorrectAnswer = ref(null);
const answerIdCounter = ref(3);

const formAddQuizDetail = useForm({
    question: null,
    answers: null,
    result: null,
    quiz_id: props.quiz.id,
})

// Add a new answer field (up to 4)
const addAnswer = () => {
    if (formAddQuizAnswers.value.length < 4) {
        formAddQuizAnswers.value.push({
            text: '',
            id: answerIdCounter.value
        });
        answerIdCounter.value++;
    }
}

// Remove an answer (keeping minimum 2)
const removeAnswer = (id) => {
    if (formAddQuizAnswers.value.length > 2) {
        formAddQuizAnswers.value = formAddQuizAnswers.value.filter(answer => answer.id !== id);

        // If selected answer was removed, reset selection
        if (selectedCorrectAnswer.value === id) {
            selectedCorrectAnswer.value = null;
        }
    }
}

// Handle form submission for adding quiz
const handleAddQuizDetail = () => {
    // Join answers with tilde (~) instead of comma
    const answersString = formAddQuizAnswers.value.map(a => a.text).join('~');

    // Get the correct answer text
    const correctAnswer = formAddQuizAnswers.value.find(a => a.id === selectedCorrectAnswer.value)?.text || '';

    formAddQuizDetail.answers = answersString;
    formAddQuizDetail.result = correctAnswer;

    formAddQuizDetail.post(route('courses.management.quiz.detail.add'), {
        onSuccess: () => {
            formAddQuizDetail.reset();
            isOpenAddQuizDetail.value = false;
        },
        preserveScroll: true,
    });
}

// AI Question Generation
const isGenerating = ref(false);
const generatedQuestions = ref([]);
const aiFormData = reactive({
    topic: "",
    numberOfQuestions: 5,
    answersPerQuestion: 4,
    difficultyLevel: "Medium",
    quiz_id: props.quiz.id,
    quiz_name:props.quiz.name
});

const difficultyOptions = ["Easy", "Medium", "Hard"];

const handleGenerateQuestions = () => {
    isGenerating.value = true;
    generatedQuestions.value = [];

    generateQuizQuestions(
        aiFormData.topic,
        aiFormData.quiz_name,
        aiFormData.numberOfQuestions,
        aiFormData.answersPerQuestion,
        aiFormData.difficultyLevel,
        (questions, error) => {
            isGenerating.value = false;

            if (error) {
                console.error("Error generating questions:", error);
                return;
            }

            generatedQuestions.value = questions.map(q => ({
                question: q.question,
                answers: q.answers,
                correctAnswer: q.correctAnswer,
                selected: true
            }));
        }
    );
}

const formGenerateQuiz = useForm({
    topic: "",
    numberOfQuestions: 5,
    answersPerQuestion: 4,
    difficultyLevel: "Medium",
    quiz_id: props.quiz.id,
    generatedQuestions: []
});

const submitGeneratedQuestions = () => {
    // Filter only selected questions
    const selectedQuestions = generatedQuestions.value
        .filter(q => q.selected)
        .map(q => ({
            question: q.question,
            answers: q.answers,
            correctAnswer: q.correctAnswer
        }));

    if (selectedQuestions.length === 0) {
        alert("Please select at least one question to add");
        return;
    }

    formGenerateQuiz.topic = aiFormData.topic;
    formGenerateQuiz.numberOfQuestions = aiFormData.numberOfQuestions;
    formGenerateQuiz.answersPerQuestion = aiFormData.answersPerQuestion;
    formGenerateQuiz.difficultyLevel = aiFormData.difficultyLevel;
    formGenerateQuiz.generatedQuestions = selectedQuestions;

    formGenerateQuiz.post(route('courses.management.quiz.detail.generate'), {
        onSuccess: () => {
            generatedQuestions.value = [];
            isOpenAddQuizDetail.value = false;
        },
        preserveScroll: true
    });
}

// Edit Quiz Modal
const isOpenEditQuizDetail = ref(false)
const formEditQuizAnswers = ref([]);
const selectedEditCorrectAnswer = ref(null);
const editAnswerIdCounter = ref(1);

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

    formEditQuizDetail.id = item.id;
    formEditQuizDetail.question = item.question;

    // Parse existing answers (splitting by tilde instead of comma)
    const answerTexts = item.answers.split('~');
    formEditQuizAnswers.value = answerTexts.map((text, index) => ({
        text: text.trim(),
        id: index + 1
    }));

    // Set the correct answer
    const correctAnswerIndex = formEditQuizAnswers.value.findIndex(a => a.text === item.result);
    if (correctAnswerIndex >= 0) {
        selectedEditCorrectAnswer.value = formEditQuizAnswers.value[correctAnswerIndex].id;
    }

    editAnswerIdCounter.value = formEditQuizAnswers.value.length + 1;
}

const closeEditQuizDetail = () => {
    isOpenEditQuizDetail.value = false;
}

// Add a new answer field in edit mode (up to 4)
const addEditAnswer = () => {
    if (formEditQuizAnswers.value.length < 4) {
        formEditQuizAnswers.value.push({
            text: '',
            id: editAnswerIdCounter.value
        });
        editAnswerIdCounter.value++;
    }
}

// Remove an answer in edit mode (keeping minimum 2)
const removeEditAnswer = (id) => {
    if (formEditQuizAnswers.value.length > 2) {
        formEditQuizAnswers.value = formEditQuizAnswers.value.filter(answer => answer.id !== id);

        // If selected answer was removed, reset selection
        if (selectedEditCorrectAnswer.value === id) {
            selectedEditCorrectAnswer.value = null;
        }
    }
}

const handleEditQuizDetail = () => {
    // Join answers with tilde (~) instead of comma
    const answersString = formEditQuizAnswers.value.map(a => a.text).join('~');

    // Get the correct answer text
    const correctAnswer = formEditQuizAnswers.value.find(a => a.id === selectedEditCorrectAnswer.value)?.text || '';

    formEditQuizDetail.answers = answersString;
    formEditQuizDetail.result = correctAnswer;

    formEditQuizDetail.put(route('courses.management.quiz.detail.update', {id: formEditQuizDetail.id}), {
        onSuccess: () => {
            formEditQuizDetail.reset();
            isOpenEditQuizDetail.value = false;
        },
        preserveScroll: true,
    });
};

// Delete Quiz
const isDelete = ref(false)
const questionName = ref(null)
const formDeleteQuiz = useForm({
    id: null
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
const handleDeleteQuiz = () => {
    formDeleteQuiz.delete(route('courses.management.quiz.detail.delete', {id: formDeleteQuiz.id}), {
        preserveScroll: true,
        onSuccess: () => {
            formDeleteQuiz.reset();
            isDelete.value = false
        }
    })
}
const back = () => {
    window.history.back();
}
</script>
<template>
    <!-- Add Quiz Modal -->
    <Modal :is-open="isOpenAddQuizDetail" @close="closeAddQuizDetail">
        <Container @click.stop class="w-[600px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Create quiz</h1>
            <TabGroup>
                <TabList class="flex items-center gap-x-2 border-b-[1px] border-gray-200 pb-4">
                    <Tab v-for="(type, index) in [{name:'Add Question Manually'},{name:'AI Generation'}]"
                         v-slot="{ selected }" :key="index">
                        <div
                            :class="['p-2 bg-[#7367F0] text-white rounded-md text-sm font-medium',selected ? 'bg-opacity-100' : 'bg-opacity-20 !text-[#7367F0]']">
                            {{ type.name }}
                        </div>
                    </Tab>
                </TabList>
                <TabPanels class="max-h-[500px] overflow-y-auto main mt-4 overflow-x-hidden">
                    <div v-auto-animate>
                        <TabPanel>
                            <form @submit.prevent="handleAddQuizDetail" class="grid space-y-4 overflow-x-hidden">
                                <InputField
                                    placeholder="Enter quiz name"
                                    label="Quiz name"
                                    v-model="formAddQuizDetail.question"
                                    :error="formAddQuizDetail.errors.question"
                                />

                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="text-sm block cursor-pointer font-normal">Answers</label>
                                        <Button
                                            v-if="formAddQuizAnswers.length < 4"
                                            @click.prevent="addAnswer"
                                            type="button"
                                            class="!py-1 px-2 text-xs"
                                        >
                                            Add Answer
                                        </Button>
                                    </div>

                                    <div class="space-y-2">
                                        <div v-for="answer in formAddQuizAnswers" :key="answer.id"
                                             class="flex items-center gap-2">
                                            <div class="flex-none">
                                                <input
                                                    type="radio"
                                                    :id="`answer-${answer.id}`"
                                                    name="correctAnswer"
                                                    :value="answer.id"
                                                    v-model="selectedCorrectAnswer"
                                                    class="mr-2"
                                                />
                                            </div>
                                            <div class="flex-grow">
                                                <input
                                                    v-model="answer.text"
                                                    type="text"
                                                    placeholder="Enter answer option"
                                                    class="w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"
                                                />
                                            </div>
                                            <div class="flex-none" v-if="formAddQuizAnswers.length > 2">
                                                <button
                                                    @click.prevent="removeAnswer(answer.id)"
                                                    type="button"
                                                    class="text-red-500"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-auto-animate>
                                        <p v-if="!!formAddQuizDetail.errors.answers" class="text-red-400 mt-1 text-sm">
                                            {{ formAddQuizDetail.errors.answers }}
                                        </p>
                                        <p v-if="!!formAddQuizDetail.errors.result" class="text-red-400 mt-1 text-sm">
                                            {{ formAddQuizDetail.errors.result }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-x-4">
                                <span
                                    @click="closeAddQuizDetail"
                                    class="border-[1px] border-gray-200 px-3 py-2 rounded-md flex items-center justify-center cursor-pointer"
                                >
                                    Cancel
                                </span>
                                    <Button
                                        type="submit"
                                        :disabled="formAddQuizDetail.processing || !selectedCorrectAnswer"
                                        class="px-3 text-sm"
                                    >
                                        Add Question
                                    </Button>
                                </div>
                            </form>
                        </TabPanel>
                    </div>
                    <div v-auto-animate>
                        <TabPanel>
                            <!-- AI Question Generation Interface -->
                            <div v-if="generatedQuestions.length === 0">
                                <div class="grid space-y-4 overflow-x-hidden">
                                    <div class="mb-4">
                                        <label class="text-sm block cursor-pointer font-normal mb-1">Topic *</label>
                                        <input
                                            v-model="aiFormData.topic"
                                            type="text"
                                            placeholder="e.g. Python basics, Data structures, Machine learning"
                                            class="w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"
                                        />
                                        <p class="text-xs text-gray-500 mt-1">The main topic for which questions will be
                                            generated</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-sm block cursor-pointer font-normal mb-1">Number of
                                                Questions</label>
                                            <input
                                                v-model="aiFormData.numberOfQuestions"
                                                type="number"
                                                min="1"
                                                max="20"
                                                class="w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"
                                            />
                                        </div>
                                        <div>
                                            <label class="text-sm block cursor-pointer font-normal mb-1">Answers per
                                                Question</label>
                                            <input
                                                v-model="aiFormData.answersPerQuestion"
                                                type="number"
                                                min="2"
                                                max="4"
                                                class="w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-sm block cursor-pointer font-normal mb-1">Difficulty
                                            Level</label>
                                        <select
                                            v-model="aiFormData.difficultyLevel"
                                            class="w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"
                                        >
                                            <option v-for="level in difficultyOptions" :key="level" :value="level">
                                                {{ level }}
                                            </option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end gap-x-4 mt-4">
                                    <span
                                        @click="closeAddQuizDetail"
                                        class="border-[1px] border-gray-200 px-3 py-2 rounded-md flex items-center justify-center cursor-pointer"
                                    >
                                        Back to Quiz
                                    </span>
                                        <Button
                                            @click="handleGenerateQuestions"
                                            :disabled="isGenerating || !aiFormData.topic"
                                            class="px-3 text-sm"
                                        >
                                            <span v-if="isGenerating">Generating...</span>
                                            <span v-else>Generate with AI</span>
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            <!-- Show generated questions -->
                            <div v-else>
                                <h2 class="font-medium mb-4">Generated Questions</h2>

                                <div class="space-y-4 mb-4">
                                    <div v-for="(question, index) in generatedQuestions" :key="index"
                                         class="border border-gray-200 p-3 rounded-lg">
                                        <div class="flex items-center gap-2 mb-2">
                                            <input
                                                type="checkbox"
                                                :id="`question-${index}`"
                                                v-model="question.selected"
                                                class="form-checkbox"
                                            />
                                            <label :for="`question-${index}`" class="font-medium">{{
                                                    question.question
                                                }}</label>
                                        </div>

                                        <div class="ml-6 mt-2">
                                            <div v-for="(answer, aIndex) in question.answers" :key="aIndex"
                                                 class="flex items-center gap-2 text-sm mb-1">
                                                <div
                                                    class="w-4 h-4 flex items-center justify-center rounded-full"
                                                    :class="answer === question.correctAnswer ? 'bg-green-500 text-white' : 'border border-gray-300'"
                                                >
                                                    <i v-if="answer === question.correctAnswer"
                                                       class="fa-solid fa-check text-xs"></i>
                                                </div>
                                                <span>{{ answer }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end gap-x-4 mt-4">
                                <span
                                    @click="generatedQuestions = []"
                                    class="border-[1px] border-gray-200 px-3 py-2 rounded-md flex items-center justify-center cursor-pointer"
                                >
                                    Back
                                </span>
                                    <Button
                                        @click="submitGeneratedQuestions"
                                        :disabled="formGenerateQuiz.processing"
                                        class="px-3 text-sm"
                                    >
                                        Add Selected Questions
                                    </Button>
                                </div>
                            </div>
                        </TabPanel>
                    </div>

                </TabPanels>
            </TabGroup>
        </Container>
    </Modal>

    <!-- Edit Quiz Modal -->
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
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-sm block cursor-pointer font-normal">Answers</label>
                        <Button
                            v-if="formEditQuizAnswers.length < 4"
                            @click.prevent="addEditAnswer"
                            type="button"
                            class="!py-1 px-2 text-xs"
                        >
                            Add Answer
                        </Button>
                    </div>

                    <div class="space-y-2">
                        <div v-for="answer in formEditQuizAnswers" :key="answer.id" class="flex items-center gap-2">
                            <div class="flex-none">
                                <input
                                    type="radio"
                                    :id="`edit-answer-${answer.id}`"
                                    name="editCorrectAnswer"
                                    :value="answer.id"
                                    v-model="selectedEditCorrectAnswer"
                                    class="mr-2"
                                />
                            </div>
                            <div class="flex-grow">
                                <input
                                    v-model="answer.text"
                                    type="text"
                                    placeholder="Enter answer option"
                                    class="w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"
                                />
                            </div>
                            <div class="flex-none" v-if="formEditQuizAnswers.length > 2">
                                <button
                                    @click.prevent="removeEditAnswer(answer.id)"
                                    type="button"
                                    class="text-red-500"
                                >
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-auto-animate>
                        <p v-if="!!formEditQuizDetail.errors.answers" class="text-red-400 mt-1 text-sm">
                            {{ formEditQuizDetail.errors.answers }}
                        </p>
                        <p v-if="!!formEditQuizDetail.errors.result" class="text-red-400 mt-1 text-sm">
                            {{ formEditQuizDetail.errors.result }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-x-4">
                    <span
                        @click="closeEditQuizDetail"
                        class="border-[1px] border-gray-200 px-3 py-2 rounded-md flex items-center justify-center cursor-pointer"
                    >
                        Cancel
                    </span>
                    <Button
                        type="submit"
                        :disabled="formEditQuizDetail.processing || !selectedEditCorrectAnswer"
                        class="px-3 text-sm"
                    >
                        Update Question
                    </Button>
                </div>
            </form>
        </Container>
    </Modal>

    <!-- Delete Confirmation Modal -->
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

    <!-- Page Content -->
    <Container class="flex justify-between items-center">
        <div class="flex items-center gap-x-4">
            <div>
                <div @click="back"
                     class="text-base underline underline-offset-[2px] text-[#8278F2] cursor-pointer"><i
                    class="fa-solid fa-arrow-left text-sm"></i> <span>Leave</span></div>

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
