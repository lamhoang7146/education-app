<script setup>
import Container from "../../Components/Container.vue";
import Modal from "../../Components/Modal.vue"
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {vAutoAnimate} from '@formkit/auto-animate';
import Button from "../../Components/Button.vue";
import {route} from "ziggy-js";

const props = defineProps({
    courses_by_AI:Object
});
const isOpen = ref(false)
const handleOpenModal = ()=>{
    isOpen.value = true
}

const handleCloseModal = ()=>{
    isOpen.value = false
}

const formSearch = useForm({
    search:""
})

const handleSubmit = ()=>{
    formSearch.post(route("courses.send.find-ai"),{
        onSuccess:()=>{
            handleCloseModal();
            formSearch.reset();
        },
        onError: (errors) => {
            console.error(errors);
        }
    })
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
}

</script>
<template>
    <Modal @close="handleCloseModal" :is-open="isOpen">
        <Container @click.stop class="w-[500px]">
            <h1 class="text-left font-medium text-lg">Hi there, What kind of course you want to find?</h1>
            <form @submit.prevent="handleSubmit" class="mt-2">
                <div>
                    <label class="text-sm block mb-2 cursor-pointer font-normal" for="descriptionAddCategory">Search course by AI</label>
                    <textarea v-model="formSearch.search" placeholder="Search course by AI..." rows="5"
                              id="descriptionAddCategory"
                              class="resize-none disabled:opacity-70 w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"></textarea>
                    <div v-auto-animate>
                        <p v-if="formSearch.errors.search" class="text-red-400 mt-1 text-sm">
                            {{ formSearch.errors.search }}</p>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4 mt-4">
                    <span @click="handleCloseModal"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button :disabled="formSearch.processing" class="px-3 text-sm">Filter</Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Container>
        <span @click="handleOpenModal" class="cursor-pointer text-center bg-[#7367F0] text-sm text-white p-2 px-4 rounded-md font-medium disabled:opacity-70 disabled:cursor-wait transition"><i class="fa-solid fa-filter text-sm mr-1"></i>Filter courses</span>
    </Container>
    <div v-if="courses_by_AI && courses_by_AI?.length > 0" class="grid grid-cols-4 gap-x-5 gap-y-5 mt-6 ">
        <Link :href="route('courses.detail',{id:item.id})" class="rounded-md overflow-hidden box-shadow-copy" v-for="item in courses_by_AI">
            <div class="relative">
                <img class="h-40 object-cover w-full" :src="`/storage/${item?.thumbnail}`" alt="">
                <div class="text-xs absolute top-4 right-4">
                                            <span v-if="item?.level?.includes('Easy')"
                                                  class="bg-green-100 py-1 px-3 rounded-full text-green-500  font-medium">Easy</span>
                    <span v-if="item?.level?.includes('Medium')"
                          class="bg-yellow-100 py-1 px-3 rounded-full text-yellow-500  font-medium">Medium</span>
                    <span v-if="item?.level.includes('Hard')"
                          class="bg-red-100 py-1 px-3 rounded-full text-red-500  font-medium">Hard</span>
                    <span v-if="item?.level?.includes('Extremely')"
                          class="bg-purple-100 py-1 px-3 rounded-full text-purple-500  font-medium">Extremely</span>
                </div>
            </div>
            <div class="p-6 bg-content dark:dark-bg-content ">
                <div class="flex items-center justify-between">
                    <div class="text-xs">
                        <span class=" px-3 py-1 font-medium  rounded-md bg-green-100 text-green-500"
                              v-if="item?.status">Active</span>
                        <span class=" px-3 py-1 rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
                    </div>
                    <div class="text-sm">
                        <span
                            v-if="!item?.is_free"
                            class="text-[#7367F0] font-medium">{{
                                formatCurrency(item.price)
                            }}</span>
                        <span v-else class="text-[#7367F0] font-medium">Free</span>
                    </div>
                </div>
                <h1 class="mt-3 mb-1 font-medium text-sm line-clamp-2 h-12">{{ item?.title }}</h1>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-x-2">
                        <div>
                            <img class="h-8 w-8 object-center rounded-full"
                                 :src="item?.user?.image ? `/storage/${item?.user?.image}` : '/storage/default.webp'" alt="">
                        </div>
                        <div class="flex flex-col justify-center">
                            <h1 class="text-sm font-medium line-clamp-1">{{ item?.user?.name }}</h1>
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
    <Container class="mt-4" v-else>Nothing</Container>
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
