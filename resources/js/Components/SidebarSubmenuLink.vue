<script setup>
import {ref} from "vue";
import {vAutoAnimate} from "@formkit/auto-animate";

const isOpen = ref(false);
defineProps({
    component:String,
    icon:String,
    routeList:Object,
    nameParent:String,
})
const emit = defineEmits(['emitSidebar'])
</script>
<template>
    <li>
        <div
            @click="isOpen = !isOpen"
            :class="{'hover-selected dark:dark-hover-selected':component.includes('Role')}"
            class="flex justify-between items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md cursor-pointer">
            <div class="flex items-center">
                <div class="mr-2">
                    <i :class="`fa-solid fa-${icon}`"></i>
                </div>
                <p>{{nameParent}}</p>
            </div>
            <div>
                <i class="fa-solid fa-angle-right"></i>
            </div>
        </div>
        <ul v-auto-animate>
            <div v-if="isOpen">
                <Link
                    v-for="route in routeList"
                    :key="route.ziggy"
                    :href="route(route.ziggy)"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === route.component}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>{{route.name}}
                </Link>
            </div>
        </ul>
    </li>

</template>
