<script setup>
import {vAutoAnimate} from '@formkit/auto-animate';

defineProps({
    label:String,
    placeholder:String,
    type:{
        type:String,
        default:"text",
    },
    error:String,
    disabled:{
        type:Boolean,
        default: false
    },
})
const model = defineModel();
</script>
<template>
    <div>
        <label class="text-sm block mb-1 cursor-pointer font-normal" :for="label">{{label}}</label>
        <input :disabled="disabled" v-model="model" autocomplete="new-password" class="disabled:opacity-70 w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent" :id="label" :type="type" :placeholder="placeholder">
        <div v-auto-animate>
            <p v-if="!!error" class="text-red-400 mt-1 text-sm">{{error}}</p>
        </div>
    </div>
</template>
