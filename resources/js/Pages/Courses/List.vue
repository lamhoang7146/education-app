<script setup>
import Container from "../../Components/Container.vue";
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {ref} from "vue";
import {route} from "ziggy-js";

const data = ref([
    {
        title: "Basics of Angular",
        des: "Introductory course for Angular and framework basics.Master Angular and build awesome apps.",
        total_videos: 5,
        tags: ['Education', 'Coding', 'Learning'],
        price: 1299000,
        voucher: 10
    },
    {
        title: "Advanced React",
        des: "Deep dive into React and state management. Build complex applications with ease.",
        total_videos: 8,
        tags: ["Web Development", "React", "JavaScript"],
        price: 1500000,
        voucher: 15
    },
    {
        title: "Python for Data Science",
        des: "Learn Python programming for data analysis and visualization. Perfect for beginners.",
        total_videos: 6,
        tags: ["Data Science", "Python", "Analytics"],
        price: 1200000,
        voucher: 20
    },
    {
        title: "Introduction to Machine Learning",
        des: "Understand the basics of machine learning and algorithms. Start your AI journey here.",
        total_videos: 7,
        tags: ["AI", "Machine Learning", "Technology"],
        price: 1800000,
        voucher: 5
    }
])

function calculateFinalPrice(price, voucher) {
    const discountPercentage = parseFloat(voucher) / 100;

    const discountAmount = price * discountPercentage;

    return price - discountAmount;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
}

const levels = ref([
    {
        level: "Selected all level"
    },
    {
        level: "Easy"
    },
    {
        level: "Medium"
    },
    {
        level: "Hard"
    },
    {
        level: "Extremely"
    },
]);
const categories = ref([
    {
        category: "Selected all categories"
    },
    {
        category: "Education"
    },
    {
        category: "Programming"
    },
    {
        category: "Machine Learning"
    },
    {
        category: "Artificial Intelligence"
    },
])
const selectedLevel = ref(levels.value[0])
const selectedCategory = ref(categories.value[0])
const search = ref("")
</script>
<template>
    <Container class="grid grid-cols-2 gap-x-4">
        <div class="grid grid-cols-2 gap-x-4">
            <div>
                <input v-model="search"
                       class="py-2 w-full outline-0 border-[1px] order-gray-200 rounded-md px-4 placeholder:text-primary placeholder:dark:dark-text-primary bg-transparent"
                       type="text" placeholder="Search courses...">
            </div>
            <Listbox v-model="selectedLevel">
                <div class="relative">
                    <ListboxButton
                        class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full flex justify-between">
                        {{ selectedLevel?.level || 'Select level' }}
                        <span><i class="fa-solid fa-angle-down"></i></span>
                    </ListboxButton>
                    <transition name="list">
                        <ListboxOptions
                            class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full">
                            <ListboxOption
                                class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                v-for="level in levels"
                                :key="level.level"
                                :value="level"
                            >
                                {{ level.level }}
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </div>
        <div class="grid grid-cols-2 gap-x-4">
            <Listbox v-model="selectedCategory">
                <div class="relative">
                    <ListboxButton
                        class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full flex justify-between">
                        {{ selectedCategory?.category || 'Select level' }}
                        <span><i class="fa-solid fa-angle-down"></i></span>
                    </ListboxButton>
                    <transition name="list">
                        <ListboxOptions
                            class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full">
                            <ListboxOption
                                class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                v-for="category in categories"
                                :key="category.category"
                                :value="category"
                            >
                                {{ category.category }}
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </div>
        <div>

        </div>
    </Container>
    <div class="mt-6 grid grid-cols-4 gap-x-4 gap-y-4">
            <Link :href="route('courses.detail',{id:1})" class="bg-content dark:dark-bg-content rounded-md box-shadow-copy" v-for="item in data">
            <div class="h-44">
                <img class="size-full rounded-tl-md rounded-tr-md" src="../../../../public/storage/courses/default.jpg"
                     alt="">
            </div>
            <div class="px-4 pt-3 pb-2">
                <span class="bg-green-200 px-2 pb-1 text-green-500 bg-opacity-40 font-medium">Easy</span>
                <h1 class="mb-1 mt-2 font-medium text-[18px] line-clamp-1">{{ item.title }}</h1>
                <div class="flex items-center justify-between">
                    <div class="space-x-2">
                        <span class="line-through text-gray-500 text-sm">{{ formatCurrency(item.price) }}</span>
                        <span
                            class="text-[#7367F0] font-medium text-base">{{
                                formatCurrency(calculateFinalPrice(item.price, item.voucher))
                            }}</span>
                    </div>
                </div>
                <div class="flex items-center my-2 justify-between">
                    <div>
                        <span class="mr-1 translate-y-[.3px]"><i class="fa-solid fa-book text-sm"></i></span>
                        {{ item.total_videos }}
                    </div>
                    <div>
                        <span class="mr-1 translate-y-[.3px]"><i class="fa-solid fa-user text-sm"></i></span>Lam Hoang
                    </div>
                    <div>
                        <span class="mr-1 translate-y-[.3px]"><i class="fa-solid fa-calendar-days text-sm"></i></span>21/2/2025
                    </div>
                </div>

            </div>


        </Link>
    </div>
</template>
<style scoped>
.list-enter-from, .list-leave-to {
    transform: translateY(-10px);
    opacity: 0;
}

.list-enter-to, .list-leave-from {
    transform: translateY(0px);
    opacity: 1;
}

.list-enter-active, .list-leave-active {
    transition: .2s ease-in-out;
}
</style>
