<template>
    <transition name="fade">
        <div @click="closeModal" v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-30">
            <slot></slot>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    isOpen:{
        type: Boolean,
        required: true,
    }
})
const emit = defineEmits(["close"])
const closeModal = ()=>{
    emit("close")
}
</script>

<style>
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}

.fade-enter-to, .fade-leave-from {
    opacity: 1;
}

.fade-enter-active, .fade-leave-active {
    transition: .2s ease-in-out;
}
</style>
