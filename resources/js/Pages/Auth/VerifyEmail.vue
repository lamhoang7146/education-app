<script setup>
import LayoutAuth from "../../Layouts/LayoutAuth.vue";
import MessageSession from "../../Components/MessageSession.vue";
defineProps({
    message: String,
    status:Boolean,
})

defineOptions({
    layout: LayoutAuth
});
</script>
<template>
    <form class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Verify your email</h1>
        <p class="opacity-70">Account activation link sent to your email, please follow the link inside to continue.</p>
        <Link :href="'/'" class="my-6 text-center bg-[#7367F0] w-full text-white py-2 rounded-md font-medium block">Skip For Now</Link>
        <MessageSession :status="status" :message="message" />
    </form>
</template>
