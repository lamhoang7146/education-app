<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import {ref} from "vue";
import CoursesContent from "../../Components/CoursesContent.vue";
import {vAutoAnimate} from "@formkit/auto-animate";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'

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
const test = ref(false)
</script>
<template>
    <div class="flex gap-x-5">

        <div class="w-2/3">
            <Container class="p-0">
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
                        <tab-panels class="max-h-[500px] overflow-y-auto main mt-4 overflow-x-hidden">
                            <div v-auto-animate>
                                <tab-panel>
                                    <h1>About</h1>
                                </tab-panel>
                            </div>
                            <div v-auto-animate>
                                <tab-panel >
                                    <div class="overflow-hidden rounded-md border-[1px] border-gray-200 dark:border-opacity-20">
                                        <CoursesContent v-for="know in 4">

                                        </CoursesContent>
                                    </div>

                                </tab-panel>
                            </div>
                        </tab-panels>
                    </TabGroup>
                </div>
                <div class="max-h-[620px] overflow-y-auto main">
                    <div class="">

                    </div>
                </div>

            </Container>
        </div>


        <div class="w-1/3">
            <Container>
                <div>
                    <div>
                        <img class="h-48 w-full rounded-md object-cover"
                             src="../../../../public/storage/courses/default.jpg" alt="">
                    </div>
                    <div class="mt-4">
                        <h1 class="font-medium leading-6 text-base mb-2">Build fullstack app using Vuejs and Laravel
                            combination with the mysql and python</h1>
                        <div class="grid grid-cols-2">
                            <div>
                                <span class="text-sm">Level:</span>
                                <span class="text-sm font-medium"> Easy</span>
                            </div>
                            <div>
                                <span class="text-sm">Modules:</span>
                                <span class="text-sm font-medium"> 6</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2">
                            <div>
                                <span class="text-sm ">Lecturers:</span>
                                <span class="text-sm font-medium"> 20</span>
                            </div>
                            <div>
                                <span class="text-sm">Quiz's:</span>
                                <span class="text-sm font-medium"> 12</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="space-x-2">
                        <span class="line-through text-gray-500 text-sm">{{ formatCurrency(1300000) }}</span>
                        <span
                            class="text-[#7367F0] font-medium text-base">{{
                                formatCurrency(calculateFinalPrice(1300000, 10))
                            }}</span>
                    </div>
                </div>
                <div class="mt-2">
                    <Link :href="route('courses.learning',{id:1})" v-if="true" class="text-center bg-[#7367F0] text-white py-2 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition block">Buy Now</Link>
                    <Button v-else>Learn courses</Button>
                </div>
            </Container>
        </div>
    </div>
</template>
