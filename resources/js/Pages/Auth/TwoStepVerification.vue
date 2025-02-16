<script setup>
import LayoutAuth from "../../Layouts/LayoutAuth.vue";
import Button from "../../Components/Button.vue";
import MessageSession from "../../Components/MessageSession.vue";
import {useForm} from "@inertiajs/vue3";

defineOptions({
    layout: LayoutAuth
});
defineProps({
    message: String,
    status: Boolean,
})
const form = useForm({});
const submit = () => {
    form.post(route('two-step-verification'))
}
</script>
<template>
    <form @submit.prevent="submit"
          class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Two Step Verification</h1>
        <p class="opacity-70">We need to verify your identity. Please click below to send the OTP code.</p>
        <div class="space-x-3 mb-6 text-center">
        </div>
        <Button class="transition-all duration-300 w-full" :disabled="form.processing">Verify my account</Button>
        <MessageSession
            class="mt-4"
            :status="status"
            :message="message"
        />
    </form>
</template>
