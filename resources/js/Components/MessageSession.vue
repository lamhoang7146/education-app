<script setup>
import {vAutoAnimate} from "@formkit/auto-animate";

defineProps({
    message: String,
    status:{
        type: Boolean,
        default: null
    }
})
</script>
<template>
    <div v-auto-animate>
        <div
            :class="[{'text-green-500':status === true},{'text-red-400':status === false}]"
            v-if="!!message"
            class="font-medium text-md text-center">
            {{ message }}
        </div>
    </div>
</template>
