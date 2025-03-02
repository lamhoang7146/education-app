<script setup >
import {ref} from "vue";
const ipOpen = ref(false);

</script>
<template>
    <div>
        <button
            @click="ipOpen = !ipOpen"
            class="hover-selected dark:dark-hover-selected px-4 py-2 w-full flex items-center justify-between border-b-[1px] border-gray-200 dark:border-opacity-20">
            <span class="font-medium">1. Introduce the NodeJs</span>
            <span class="flex items-center gap-x-2">3 Lecturers <i
                :class="`fa-solid fa-angle-${ipOpen ? 'up' : 'down'} translate-y-[.5px]`"></i></span>
        </button>
        <div :class="['grid transition-all duration-300 grid-rows-[0fr] :las', ipOpen ? 'grid-rows-[1fr]' : '']">
            <div class="overflow-hidden">
                <div class="border-b-[1px] border-gray-200 py-2 px-4 flex items-center justify-between dark:border-opacity-20">
                    <span><i class="fa-solid fa-video mr-2 text-sm"></i>Setup nodejs environment.</span>
                    <span>3:10</span>
                </div>
                <div class="border-b-[1px] border-gray-200 py-2 px-4 flex items-center justify-between dark:border-opacity-20">
                    <span><i class="fa-solid fa-pen mr-2 text-sm"></i>Setup nodejs environment.</span>
                    <span>3:10</span>
                </div>
            </div>
        </div>
    </div>
</template>
