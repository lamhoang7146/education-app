<script setup>
import { ref } from "vue";
import { defineProps } from "vue";
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
const params = route().params
const props = defineProps({
    content: {
        type: Object,
        required: true
    }
});
const isOpen = ref(false);

// Function to determine content type icon
const getContentIcon = (type) => {
    return type === 'video' ? 'fa-video' : 'fa-pen';
};

// Function to format time (assuming duration is stored in seconds)
const formatTime = (seconds) => {
    if (!seconds) return '--:--';
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
};

const handleRequest = (id,type,content_id)=>{
        router.get(route('courses.learning',{id,type,content_id}))
}

</script>

<template>
    <div>
        <button
            @click="isOpen = !isOpen"
            class="hover-selected dark:dark-hover-selected px-4 py-2 w-full flex items-center justify-between border-b-[1px] border-gray-200 dark:border-opacity-20"
        >
            <span class="font-medium">{{ content.name }}</span>
            <span class="flex items-center gap-x-2">
                {{ content.content_items.length }} Lecturers
                <i :class="`fa-solid fa-angle-${isOpen ? 'up' : 'down'} translate-y-[.5px]`"></i>
            </span>
        </button>
        <div :class="['grid transition-all duration-300 grid-rows-[0fr]', isOpen ? 'grid-rows-[1fr]' : '']">
            <div class="overflow-hidden">
                <div
                    v-for="(item, index) in content.content_items"
                    :key="index"
                    @click="handleRequest(content.courses_id,item.content_type,item.content_id)"
                    class="border-b-[1px] border-gray-200 py-2 px-4 flex items-center justify-between dark:border-opacity-20 cursor-pointer"
                    :class="{'bg-slate-300':params.type === item.content_type && parseInt(params.content_id) === parseInt(item.content_id)}"
                >

                        <span>
                        <i :class="`fa-solid ${getContentIcon(item.content_type)} mr-2 text-sm`"></i>
                        {{ item.content?.name || 'Untitled Content' }}
                        </span>
                    <span v-if="item.content_type === 'video'">{{ formatTime(item.content?.duration) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
