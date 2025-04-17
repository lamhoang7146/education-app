<script setup>
import {computed, ref} from "vue";
const emit = defineEmits(['emitSidebar'])
import {switchThemeOnLoad,setThemeOnLoad} from '../theme.js';
import {usePage} from "@inertiajs/vue3";
const mode = ref(setThemeOnLoad());
const user = computed(() => usePage().props.auth.user);
const isMode = computed(() => mode.value);
import { Menu, MenuButton, MenuItems } from '@headlessui/vue'
import {route} from "ziggy-js";

function switchTheme() {
    switchThemeOnLoad();
    mode.value = localStorage.theme;
}
</script>
<template>
    <div
        class="h-[54px] transition-all !fixed top-4 xl:left-[280px] right-[20px] left-[20px] rounded-md z-40 bg-content dark:dark-bg-content box-shadow-copy px-6 flex items-center justify-between">
        <div class="flex items-center text-primary dark:dark-text-primary">
            <i  @click="emit('emitSidebar')" class="fa-solid fa-bars xl:hidden block mr-4 cursor-pointer text-xl"></i>
            <i class="fa-solid fa-magnifying-glass mr-3"></i>
            <div class="flex items-center">
                <span class="mr-1 -translate-y-[2px] font-medium">Search</span>
                <i class="fa-solid fa-list-ul"></i>
            </div>
        </div>
        <div class="text-primary dark:dark-text-primary transition-all flex items-center gap-x-5">
            <transition mode="out-in">
                <i v-if="isMode === 'light'" @click="switchTheme" class="fa-solid fa-moon cursor-pointer"></i>
                <i v-else @click="switchTheme" class="fa-solid fa-sun cursor-pointer"></i>
            </transition>
            <Link v-if="!user" :href="route('login')">
                <i class="fa-solid fa-user"></i>

            </Link>
            <!--      If user is exist      -->
            <Menu as="div" class="relative" v-if="user">
                <MenuButton>
                    <img class="size-10 rounded-full cursor-pointer" src="../../../public/storage/default.webp" alt="">
                </MenuButton>
                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                    <MenuItems class="absolute w-60 py-2 rounded-md bg-content dark:dark-bg-content top-16 right-0 box-shadow-copy">
                        <div class="mx-2 my-2 px-4 flex items-center space-x-2">
                            <img class="size-10 rounded-full" src="../../../public/storage/default.webp" alt="">
                            <div>
                                <h1 class="font-medium">{{user.name}}</h1>
                            </div>
                        </div>
                        <ul class="border-t border-b dark:border-gray-600 my-4">
                            <li class="my-2 mx-2 py-2 px-4 flex items-center gap-x-4">
                                <i class="fa-solid fa-user"></i>
                                <span>Profile</span>
                            </li>
                        </ul>
                        <div class="px-2">
                            <Link
                                method="post" as="button"
                                class="rounded-md text-white text-sm font-medium my-2 w-full px-2 py-2 bg-red-500 flex items-center justify-center space-x-2 "
                                :href="route('logout')"><span>Logout</span><i class="fa-solid fa-right-from-bracket text-sm translate-y-[1px]"></i></Link>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>

        </div>

    </div>
</template>
<style scoped>
.v-enter-from,.v-leave-to{
    transform: scale(0);
    opacity: 0;
}
.v-enter-to,.v-leave-from{
    transform: scale(1);
    opacity: 1;
}
.v-enter-active,.v-leave-active{
    transition: .15s ease-in-out;
}

</style>
