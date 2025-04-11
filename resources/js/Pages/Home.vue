<script setup>
import Container from '../Components/Container.vue';
import { route } from 'ziggy-js';
import {onMounted} from "vue";
const props = defineProps({
    categories: Object
})
import {ref} from 'vue';
const goals = ref([
    {
        id:1,
        title: 'Get a job',
        description: 'Learn the skills you need to get hired.',
        image: 'desktop-hands-on-learning-2x.webp',
        icon: 'hands-on-practice.png'
    },
    {
        id:2,
        title: 'Get a promotion',
        description: 'Learn the skills you need to get promoted.',
        image: 'desktop-certification-prep-2x.png',
        icon: 'certificate.png'
    },
    {
        id:3,
        title: 'Change careers',
        description: 'Learn the skills you need to change careers.',
        image: 'desktop-insights-and-analytics-2x.webp',
        icon: 'empty-state-1.png'
    },
    {
        id:4,
        title: 'Start a business',
        description: 'Learn the skills you need to start a business.',
        image: 'desktop-customizable-2x.png',
        icon: 'organizations-2.png'
    }
])
const defaultImage = ref(goals.value[0].image);

// Dữ liệu cho phần slide carousel
const caseStudies = ref([
    {
        id: 1,
        title: 'Booz Allen Hamilton Unlocks Talent Retention and Productivity Through Upskilling',
        stats: [
            { value: '93%', description: 'retention rate among participating employees' },
            { value: '65%', description: 'of learners noted a positive impact on their productivity' }
        ],
        image: 'UB_Case_Studies_Booz_Allen_image.webp'
    },
    {
        id: 2,
        title: 'Company B Achieves Remarkable Results Through Galaxy Learning Platform',
        stats: [
            { value: '87%', description: 'increase in employee skill proficiency' },
            { value: '72%', description: 'of participants completed advanced certifications' }
        ],
        image: 'UB_Case_Studies_Booz_Allen_image.webp'
    },
    {
        id: 3,
        title: 'Organization C Transforms Their Workforce with Strategic Learning',
        stats: [
            { value: '78%', description: 'reduction in onboarding time for new employees' },
            { value: '91%', description: 'of employees felt more confident in their roles' }
        ],
        image: 'UB_Case_Studies_Booz_Allen_image.webp'
    },
    {
        id: 4,
        title: 'Enterprise D Leverages Galaxy to Drive Innovation and Growth',
        stats: [
            { value: '82%', description: 'of teams reported improved collaboration' },
            { value: '59%', description: 'increase in project completion efficiency' }
        ],
        image: 'UB_Case_Studies_Booz_Allen_image.webp'
    }
]);
const currentSlideIndex = ref(0);
const isTransitioning = ref(false);

// Hàm xử lý chuyển slide
const goToSlide = (index) => {
    if (isTransitioning.value) return;
    isTransitioning.value = true;
    currentSlideIndex.value = index;
    setTimeout(() => {
        isTransitioning.value = false;
    }, 500); // Thời gian chờ cho hiệu ứng transition
};

// Chuyển đến slide tiếp theo
const nextSlide = () => {
    const newIndex = (currentSlideIndex.value + 1) % caseStudies.value.length;
    goToSlide(newIndex);
};

// Chuyển đến slide trước đó
const prevSlide = () => {
    const newIndex = (currentSlideIndex.value - 1 + caseStudies.value.length) % caseStudies.value.length;
    goToSlide(newIndex);
};

// Auto play slide (tùy chọn)
let slideInterval;
const startAutoPlay = () => {
    slideInterval = setInterval(() => {
        nextSlide();
    }, 5000); // Chuyển slide mỗi 5 giây
};

const stopAutoPlay = () => {
    clearInterval(slideInterval);
};

onMounted(() => {
    startAutoPlay(); // Bắt đầu tự động chạy slide khi component được mount
});
</script>
<template>
    <div class="relative">
        <img draggable="false" class="w-full rounded-md overflow-hidden" src="/public/storage/banner/banner.png" alt="">
        <Container class="absolute top-[40%] left-10 translate-y-[-50%] w-[30%]">
            <h1 class="text-xl font-medium">Learning comes to you
            </h1>
            <p class=" mt-2">Explore any interest or trending topic, take prerequisites, and advance your skills.</p>
        </Container>
    </div>
    <Container class="mt-4 ">
        <h1 class="text-xl font-medium">Advance Your Career. Learn In-demand Skills.</h1>
        <p>Upskill in business analytics, health care, graphic design, management and more.</p>
        <div class="space-x-6 mt-4">
            <Link class="px-4 py-2 border-[1px] border-gray-200 rounded-full hover:hover-selected transition duration-300" :href="route('courses.index')" :data="{category_courses_id:category.id}" v-for="category in categories">{{category.name}}</Link>
        </div>
    </Container>
    <Container class="mt-4">
        <div class="my-4">
            <h1 class="text-xl font-medium">All the skills you need in one place</h1>
            <p>From critical skills to technical topics, Galaxy supports your professional development.</p>
        </div>
        <div class="grid grid-cols-4 gap-x-4">
            <div v-for="i in 4">
                <div></div>
                <Container></Container>
            </div>
        </div>
    </Container>
    <Container class="my-4">
        <h1 class="text-xl font-medium">Learning focused on your goals</h1>
        <div class="grid grid-cols-[1fr_1fr] gap-x-4">
            <div class="grid gap-y-4 mt-4">
                <Container v-for="(goal,index) in goals" @click="defaultImage = goal.image" class="flex items-center gap-x-4 cursor-pointer" :class="{'bg-gray-100 dark:bg-gray-800':goal.image.includes(defaultImage) }">
                    <div>
                        <img draggable="false" class="h-10 w-10 rounded-md overflow-hidden text-white bg-white" :src="`/storage/goals/${goal.icon}`" alt="">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">{{goal.title}}</h1>
                        <p>{{goal.description}}</p>
                    </div>
                </Container>
            </div>
            <div>
                <img draggable="false" class="w-full h-full" :src="`/storage/goals/${defaultImage}`" alt="">
            </div>
        </div>
    </Container>
    <Container>
        <h1 class="text-xl font-medium">See what others are achieving through learning</h1>
        <div class="grid grid-cols-4 mt-4 gap-x-4">
            <Container>
                <i class="fa-solid fa-quote-left"></i>
                <p class="leading-4 text-sm">Galaxy was truly <strong>a game-changer and a great guide for me</strong> as we brought Dimensional to life.</p>
                <div class="mt-4 flex items-center gap-x-2">
                    <div class="h-12 w-12">
                        <img class=" overflow-hidden rounded-full" src="/public/storage/profile/Alvin_Lim.webp" alt="">
                    </div>
                    <div>
                        <h1 class="text-xs font-medium">Alvin Lim</h1>
                        <p class="text-xs">Technical Co-Founder, CTO at Dimensional</p>
                    </div>
                </div>
            </Container>
            <Container>
                <i class="fa-solid fa-quote-left"></i>
                <p class="leading-4 text-sm">Galaxy was truly <strong>a game-changer and a great guide for me</strong> as we brought Dimensional to life.</p>
                <div class="mt-4 flex items-center gap-x-2">
                    <div class="h-12 w-12">
                        <img class=" overflow-hidden rounded-full" src="/public/storage/profile/Alvin_Lim.webp" alt="">
                    </div>
                    <div>
                        <h1 class="text-xs font-medium">Alvin Lim</h1>
                        <p class="text-xs">Technical Co-Founder, CTO at Dimensional</p>
                    </div>
                </div>
            </Container>
            <Container>
                <i class="fa-solid fa-quote-left"></i>
                <p class="leading-4 text-sm">Galaxy was truly <strong>a game-changer and a great guide for me</strong> as we brought Dimensional to life.</p>
                <div class="mt-4 flex items-center gap-x-2">
                    <div class="h-12 w-12">
                        <img class=" overflow-hidden rounded-full" src="/public/storage/profile/Alvin_Lim.webp" alt="">
                    </div>
                    <div>
                        <h1 class="text-xs font-medium">Alvin Lim</h1>
                        <p class="text-xs">Technical Co-Founder, CTO at Dimensional</p>
                    </div>
                </div>
            </Container>
            <Container>
                <i class="fa-solid fa-quote-left"></i>
                <p class="leading-4 text-sm">Galaxy was truly <strong>a game-changer and a great guide for me</strong> as we brought Dimensional to life.</p>
                <div class="mt-4 flex items-center gap-x-2">
                    <div class="h-12 w-12">
                        <img class=" overflow-hidden rounded-full" src="/public/storage/profile/Alvin_Lim.webp" alt="">
                    </div>
                    <div>
                        <h1 class="text-xs font-medium">Alvin Lim</h1>
                        <p class="text-xs">Technical Co-Founder, CTO at Dimensional</p>
                    </div>
                </div>
            </Container>
        </div>
    </Container>
    <Container class="my-4 grid grid-cols-[1fr_2fr]">
        <div class="flex items-center">
            <div class="w-[90%]">
                <h1 class="text-xl font-medium">Top trends for the future of work</h1>
                <p class="my-4">Our 2025 Global Learning & Skills Trends Report is out now! Find out how to build the skills to keep pace with change.</p>
            </div>
        </div>
        <div>
            <img draggable="false" class="w-full" src="/public/storage/banner/Onsite_Desktop_GLSTR25.webp" alt="">
        </div>
    </Container>
    <div class="relative carousel-container overflow-hidden">
        <div
            class="carousel-slides transition-transform duration-500 ease-in-out"
            :style="{ transform: `translateX(-${currentSlideIndex * 100}%)` }"
        >
            <div
                v-for="(study, index) in caseStudies"
                :key="study.id"
                class="carousel-slide w-full flex-shrink-0"
            >
                <div class="grid grid-cols-2">
                    <div class="w-[90%] flex items-center">
                        <div>
                            <h1 class="text-2xl font-bold">{{ study.title }}</h1>
                            <div class="grid grid-cols-2 mt-4 gap-x-4">
                                <div v-for="(stat, statIndex) in study.stats" :key="statIndex" class="border-b-[2px] border-gray-200">
                                    <h1 class="text-5xl font-bold">{{ stat.value }}</h1>
                                    <p class="mt-2 mb-2 font-medium">{{ stat.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <img draggable="false" class="w-full" :src="`/storage/banner/${study.image}`" alt="">
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls for the carousel -->
        <div class="absolute bottom-[2%] left-[10%] transform -translate-x-1/2 flex items-center space-x-4">
            <button
                @click="prevSlide"
                @mouseenter="stopAutoPlay"
                @mouseleave="startAutoPlay"
                class="h-10 w-10 bg-content flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors dark:dark-bg-content"
            >
                <i class="fa-solid fa-angle-left"></i>
            </button>

            <div class="flex space-x-2">
                <button
                    v-for="(study, index) in caseStudies"
                    :key="`dot-${study.id}`"
                    @click="goToSlide(index)"
                    class="h-3 w-3 rounded-full transition-colors"
                    :class="index === currentSlideIndex ? 'bg-[#8278F2]' : 'bg-gray-300'"
                ></button>
            </div>

            <button
                @click="nextSlide"
                @mouseenter="stopAutoPlay"
                @mouseleave="startAutoPlay"
                class="h-10 w-10 bg-content flex items-center justify-center rounded-full hover:bg-gray-200 transition-colors dark:dark-bg-content"
            >
                <i class="fa-solid fa-angle-right"></i>
            </button>
        </div>
    </div>
</template>
<style scoped>
.carousel-container {
    width: 100%;
    position: relative;
}

.carousel-slides {
    display: flex;
    width: 100%;
}

.carousel-slide {
    width: 100%;
}

/* Thêm các style khác nếu cần */
</style>
