<script setup>

import Header from "./Header.vue";
import Sidebar from "./Sidebar.vue";
import {ref} from "vue";
const sidebar = ref(false);
const handleSidebar = () => {
    sidebar.value = !sidebar.value;
}
const setFalseSidebar = () => {
    sidebar.value = false;
}
</script>
<template>
    <transition name="fade">
        <div v-if="sidebar" @click="handleSidebar" class="overlay"></div>
    </transition>
    <Sidebar  @emitSidebar="setFalseSidebar" :sidebar="sidebar"></Sidebar>
    <div class="xl:pl-[280px] px-5 transition-all">
        <Header @emitSidebar="handleSidebar" ></Header>
        <div class="mt-24">
            <slot></slot>
        </div>
    </div>

</template>
<style scoped>
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 45;
    transition: .3s ease-in-out;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
