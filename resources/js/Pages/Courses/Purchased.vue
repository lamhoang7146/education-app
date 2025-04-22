<script setup>
import {route} from "ziggy-js";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import Container from "../../Components/Container.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
const params = route().params;
const props = defineProps({
    purchased_courses:Object,
    category_courses:Object
})

let selectedCategory = ref(props.category_courses.find(item=> item.id === parseInt(params.category_courses_id)));
const categoryCoursesParams = ref([{id:null,name:'Select category'},...props.category_courses]);
watch(selectedCategory, (newCategory) => {
    router.get(route('courses.purchased'), {
        category_courses_id: newCategory ? newCategory.id : null,
    })
})

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
}

</script>
<template>
    <Container class="flex items-center gap-x-6">
        <h1 class="text-lg font-medium">Your purchased courses</h1>
        <div class="w-[300px]">
            <Listbox class="w-[300px]" v-model="selectedCategory">
                <div class="relative w-full">
                    <ListboxButton class="border-[1px] w-full border-gray-200 rounded-md py-2 px-4 text-left text-sm">
                        {{ selectedCategory?.name || 'Select category' }}
                    </ListboxButton>
                    <transition name="list">
                        <ListboxOptions
                            class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full z-30">
                            <ListboxOption
                                class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                v-for="item in categoryCoursesParams"
                                :key="item.id"
                                :value="item">
                                {{ item.name }}
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </div>
    </Container>
    <div v-if="purchased_courses.data.length > 0">
        <div class="grid grid-cols-4 gap-x-5 gap-y-5 mt-6 ">
            <Link :href="route('courses.detail',{id:item.id})" class="rounded-md overflow-hidden box-shadow-copy" v-for="item in purchased_courses.data">
                <div class="relative">
                    <img class="h-40 object-cover w-full" :src="`/storage/${item.thumbnail}`" alt="">
                    <div class="text-xs absolute left-4 top-3 bg-gray-200 py-1 px-3 text-black font-medium rounded-full">{{ item.category_name }}</div>
                    <div class="text-xs absolute top-4 right-4">
                                            <span v-if="item.level.includes('Easy')"
                                                  class="bg-green-100 py-1 px-3 rounded-full text-green-500  font-medium">Easy</span>
                        <span v-if="item.level.includes('Medium')"
                              class="bg-yellow-100 py-1 px-3 rounded-full text-yellow-500  font-medium">Medium</span>
                        <span v-if="item.level.includes('Hard')"
                              class="bg-red-100 py-1 px-3 rounded-full text-red-500  font-medium">Hard</span>
                        <span v-if="item.level.includes('Extremely')"
                              class="bg-purple-100 py-1 px-3 rounded-full text-purple-500  font-medium">Extremely</span>
                    </div>
                </div>
                <div class="p-6 bg-content dark:dark-bg-content ">
                    <div class="flex items-center justify-between">
                        <div class="text-xs">
                        <span class=" px-3 py-1 font-medium  rounded-md bg-green-100 text-green-500"
                              v-if="item.status">Active</span>
                            <span class=" px-3 py-1 rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
                        </div>
                        <div class="text-sm">
                        <span
                            v-if="!item.is_free"
                            class="text-[#7367F0] font-medium">{{
                                formatCurrency(item.price)
                            }}</span>
                            <span v-else class="text-[#7367F0] font-medium">Free</span>
                        </div>
                    </div>
                    <h1 class="mt-3 mb-1 font-medium text-sm line-clamp-2 h-12">{{ item.title }}</h1>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-x-2">
                            <div>
                                <img class="h-8 w-8 object-center rounded-full"
                                     :src="item.user.image ? `/storage/${item.user.image}` : '/storage/default.webp'" alt="">
                            </div>
                            <div class="flex flex-col justify-center">
                                <h1 class="text-sm font-medium">{{ item.user.name }}</h1>
                                <p>{{ item.user.email }}</p>
                            </div>
                        </div>
                        <div class="text-sm flex items-center gap-x-2">
                            <i class="fa-regular fa-clock text-sm"></i>
                            <span class="-translate-y-[.5px]">
                            {{ item?.created_at }}
                        </span>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
        <PaginationLinks class="my-4" :paginator="purchased_courses" />
    </div>
    <div v-else>
        <Container class="mt-4" >Nothing</Container>
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
