<script setup>
import {ref} from "vue";
import {vAutoAnimate} from "@formkit/auto-animate";
const state = ref(false)
defineProps({
    component:{
        required:true,
        type:String,
    },
    reference:{
        required:true,
        type:String,
    },
    name:{
        type:String,
        required:true,
    },
    icon:{
        type:String,
        required:true,
    },
})
</script>
<template>
    <li>
        <div
            @click="state = !state"
            :class="{'hover-selected dark:dark-hover-selected':component.includes(reference)}"
            class="flex justify-between items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md cursor-pointer">
            <div class="grid grid-cols-[35px_1fr]">
                <div>
                    <i :class="`fa-solid ${icon}`"></i>
                </div>
                <p>{{name}}</p>
            </div>
            <div>
                <i
                    :class="state ? 'transform rotate-90' : 'transform rotate-0'"
                    class="fa-solid fa-angle-right transition"></i>
            </div>
        </div>
        <ul v-auto-animate>
            <div v-if="state">
                <slot></slot>
            </div>
        </ul>
    </li>

</template>
