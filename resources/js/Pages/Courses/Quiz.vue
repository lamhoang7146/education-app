<script setup>
import { ref, reactive, computed, onMounted, onBeforeUnmount } from 'vue';
import {router} from "@inertiajs/vue3";
import Container from "../../Components/Container.vue";
import {route} from "ziggy-js";

const props = defineProps({
    data: Object
});

const radius = ref(null);
const quizzes = reactive([]);
const listSubmit = reactive([]);
const listResult = reactive([]);
const ABD = reactive(['A', 'B', 'C', 'D']);
let currentIndex = ref(0);
let isSubmit = ref(false);
let countCorrect = ref(0);
let countIncorrect = ref(0);

// Format the data for quiz display
const formatQuizData = (data) => {
    return data.quiz_content_details.map(item => {
        return {
            question: item.question,
            answers: item.answers.split('~'),
            correctAnswer: item.result
        };
    });
};

// Computed properties
const getAnswerDetail = computed(() => quizzes[currentIndex.value]?.answers || []);
const getQuestion = computed(() => quizzes[currentIndex.value]?.question);
const getTotalQuestion = computed(() => quizzes);
const getPosition = computed(() => `${currentIndex.value + 1}/${getTotalQuestion.value.length}`);
const getProgressText = computed(() => {
    if (isSubmit.value) {
        return `${countCorrect.value}/${quizzes.length}`;
    } else {
        return `${listSubmit.filter(isHasIndex => isHasIndex !== undefined).length}/${quizzes.length}`;
    }
});

const calculatorProgress = computed(() => {
    const circleLength = 2 * Math.PI * 70;
    let progress;

    if (isSubmit.value) {
        progress = (countCorrect.value / quizzes.length) * circleLength;
    } else {
        progress = (listSubmit.filter(item => item !== undefined).length / quizzes.length) * circleLength;
    }

    return {
        'stroke-dasharray': `${progress}px ${circleLength}px`
    };
});

// Methods
const submit = () => {
    if (!isSubmit.value) {
        const dataSubmit = listSubmit.filter(item => item !== undefined);
        if (dataSubmit.length === quizzes.length) {
            isSubmit.value = true;
            quizzes.forEach((item, index) => {
                if (item?.answers[listSubmit[index]] === item?.correctAnswer) {
                    countCorrect.value++;
                } else {
                    countIncorrect.value++;
                }
                listResult[index] = item?.answers.indexOf(item?.correctAnswer);
            });
            handleSendDataOfUser();
        } else {
            alert('Please answer all questions before submitting!');
        }
    }
};

const handleSendDataOfUser = () => {
    // Using Inertia.js to submit the data to Laravel
    console.log()
    // router.post('/quiz/submit', {
    //     quizId: props.data.id,
    //     correctAnswers: countCorrect.value,
    //     incorrectAnswers: countIncorrect.value,
    // });
};

const setCurrentIndex = (index = 0) => {
    currentIndex.value = index;
};

const handleKeyDown = e => {
    const type = e.keyCode;
    if (type === 39) {
        handleNext();
    } else if (type === 37) {
        handlePrev();
    }
};

const handleNext = () => {
    if (currentIndex.value < quizzes.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0;
    }
};

const handlePrev = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = quizzes.length - 1;
    }
};

const setAnswer = indexAnswer => {
    listSubmit[currentIndex.value] = indexAnswer;
};

const turnBack = ()=>{
    router.get(route('courses.detail',{id:parseInt(route()?.params?.id)}))
}

onMounted(() => {
    // Using the data directly from props
    const formattedQuizData = formatQuizData(props.data);
    quizzes.push(...formattedQuizData);

    window.addEventListener('keydown', handleKeyDown);
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
    <div>
        <Container>
            <!-- Quiz Header -->
            <div @click="turnBack" class="text-[#8278F2] cursor-pointer hover:underline font-medium">Leave</div>
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center gap-3">
                    <div>
                        <p class="bg-[#8278F2] text-white px-4 py-1 rounded-full text-sm font-semibold">
                            {{ getPosition }}
                        </p>
                    </div>
                </div>
                <button v-if="!isSubmit"
                        @click="submit"
                        class="px-5 py-2 rounded-lg border-2 border-[#8278F2] hover:bg-[#8278F2] hover:text-white transition-colors duration-300 font-medium">
                    Submit
                </button>
                <div v-else
                     @click="turnBack"
                   class="px-5 py-2 rounded-lg bg-[#8278F2] text-white hover:bg-[#8278F2] transition-colors duration-300 font-medium cursor-pointer">
                    Turn back
                </div>
            </div>

            <!-- Quiz Content -->
            <div class="flex flex-col lg:flex-row gap-6 my-10">
                <!-- Question Section -->
                <div class="flex-1">
                    <h5 class="text-base font-bold mb-4">{{ getQuestion }}</h5>
                    <ul class="grid md:grid-cols-2 gap-4 mt-4">
                        <li v-for="(answer, index) in getAnswerDetail"
                            :key="index"
                            @click="!isSubmit ? setAnswer(index) : null"
                            :class="[
                  'p-5 rounded-lg shadow cursor-pointer flex items-center text-base transition-colors',
                  {
                    'bg-[#8278F2] text-white': !isSubmit ? listSubmit[currentIndex] === index : listSubmit[currentIndex] === listResult[currentIndex] ? listSubmit[currentIndex] === index : listResult[currentIndex] === index,
                    'bg-red-600 text-white': isSubmit && listSubmit[currentIndex] === index && listSubmit[currentIndex] !== listResult[currentIndex],
                    'hover:hover-selected dark:hover:dark-hover-selected': !isSubmit && listSubmit[currentIndex] !== index
                  }
                ]">
                            <span class="mr-3 font-bold">{{ ABD[index] }}.</span>
                            <span>{{ answer }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Progress Section -->
                <div class="w-full lg:w-64 flex justify-center">
                    <div class="relative w-48 h-48">
                        <svg class="w-full h-full -rotate-90">
                            <circle cx="96" cy="96" r="70" class="fill-none stroke-gray-200 stroke-[15]" />
                            <circle cx="96" cy="96" r="70" ref="radius"
                                    class="fill-none stroke-[#8278F2] stroke-[15] stroke-linecap-round transition-all duration-500"
                                    :style="calculatorProgress" />
                        </svg>
                        <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-xl font-bold text-[#8278F2]">
              {{ getProgressText }}
            </span>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex items-center gap-4 mt-10">
                <button @click="handlePrev"
                        class="px-5 py-2 rounded-lg bg-[#8278F2] text-white hover:bg-[#8278F2] transition-colors">
                    Prev
                </button>
                <ul class="flex gap-3 overflow-x-auto py-1 flex-1 no-scrollbar">
                    <li v-for="(_, index) in getTotalQuestion"
                        :key="index"
                        @click="setCurrentIndex(index)"
                        :class="[
                'w-10 h-10 flex items-center  justify-center rounded-lg shadow transition-colors cursor-pointer',
                {
                  'border-2 border-[#8278F2] ': currentIndex === index && !isSubmit,
                  'bg-[#8278F2]': listSubmit[index] !== undefined && !isSubmit && currentIndex !== index,
                  'bg-red-600 text-white': isSubmit && listSubmit[index] !== listResult[index],
                  'bg-[#8278F2] text-white': isSubmit && listSubmit[index] === listResult[index]
                }
              ]">
                        {{ index + 1 }}
                    </li>
                </ul>
                <button @click="handleNext"
                        class="px-5 py-2 rounded-lg bg-[#8278F2] hover:bg-[#8278F2] text-white transition-colors">
                    Next
                </button>
            </div>
        </Container>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
