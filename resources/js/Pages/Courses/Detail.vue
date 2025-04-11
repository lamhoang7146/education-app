<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import {ref} from "vue";
import CoursesContent from "../../Components/CoursesContent.vue";
import {vAutoAnimate} from "@formkit/auto-animate";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
const props = defineProps({
    courses_detail: {
        type: Object,
        required: true
    },
    content_count: {
        type: Number,
        required: true
    }
});
console.log(props.courses_detail)
// Course data
const course = props.courses_detail;
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

const categories = ref([
    {
        type: 'Description',
    },
    {
        type: 'Courses contents',
    }
])

</script>
<template>
    <div class="grid grid-cols-[2fr_1fr] gap-x-5 w-full">
        <div class="">
            <Container class="p-1">
                <div>
                    <TabGroup>
                        <TabList class="flex items-center gap-x-2 border-b-[1px] border-gray-200 pb-4">
                            <Tab v-for="category in categories" v-slot="{ selected }">
                                <div
                                    :class="['p-2 bg-[#7367F0] text-white rounded-md text-sm font-medium',selected ? 'bg-opacity-100' : 'bg-opacity-20 !text-[#7367F0]']">
                                    {{ category.type }}
                                </div>
                            </Tab>
                        </TabList>
                        <TabPanels v-auto-animate class="max-h-[500px] overflow-y-auto main mt-4 overflow-x-hidden">
                            <transition name="fade" mode="out-in">
                                <TabPanel key="desc">
                                    <div class="tiptap pr-2" v-html="courses_detail.description"></div>
                                </TabPanel>
                            </transition>

                            <transition name="fade" mode="out-in">
                                <TabPanel key="content">
                                    <div v-if="course.courses_contents.length > 0" class="overflow-hidden rounded-md border-[1px] border-gray-200 dark:border-opacity-20">
                                        <CoursesContent
                                            v-for="content in course?.courses_contents"
                                            :key="content.id"
                                            :content="content"
                                        />
                                    </div>
                                    <div v-else>
                                        <h1>Don't have courses content!</h1>
                                    </div>
                                </TabPanel>
                            </transition>
                        </TabPanels>
                    </TabGroup>
                </div>


            </Container>
        </div>
        <div class="">
            <Container>
                <div>
                    <div>
                        <img class="h-48 w-full rounded-md object-cover"
                             :src="`/storage/${course.thumbnail}`" alt="">
                    </div>
                    <div class="mt-4">
                        <h1 class="font-medium leading-6 text-base mb-2">{{courses_detail.title}}</h1>
                        <div class="grid grid-cols-2">
                            <div>
                                <span class="text-sm">Level:</span>
                                <span class="text-sm font-medium">{{` ${courses_detail.level}`}}</span>
                            </div>
                            <div>
                                <span class="text-sm">Modules:</span>
                                <span class="text-sm font-medium"> {{` ${course.courses_contents.length}`}}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2">
                            <div>
                                <span class="text-sm ">Lecturers:</span>
                                <span class="text-sm font-medium">{{` ${course.user.name}`}}</span>
                            </div>
                            <div>
                                <span class="text-sm">Created at:</span>
                                <span class="text-sm font-medium"> {{` ${course.created_at}`}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div v-if="!course.is_free" class="space-x-2">
                        <span class="line-through text-gray-500 text-sm">{{ formatCurrency(parseInt(course.price)) }}</span>
                        <span
                            class="text-[#7367F0] font-medium text-base">{{
                                formatCurrency(calculateFinalPrice(parseInt(course.price), 10))
                            }}</span>
                    </div>
                </div>
                <div  class="mt-2">
                    <Link :href="route('courses.learning',{id:1})" v-if="!course.is_free" class="text-center bg-[#7367F0] text-white py-2 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition block">Buy Now</Link>
                    <Link :href="route('courses.learning',{id:1})" v-else class="text-center bg-[#7367F0] text-white py-2 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition block">Learn Now</Link>

                </div>
            </Container>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease-in-out;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
