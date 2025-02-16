<script setup>
import LayoutAuth from "../../Layouts/LayoutAuth.vue";

defineOptions({
    layout: LayoutAuth,
})
defineProps({
    message: String,
    status: Boolean,
})
import InputFieldOTP from "../../Components/InputFieldOTP.vue";
import MessageSession from "../../Components/MessageSession.vue";
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    OTP: null
})
const submit = (v) => {
    form.OTP = parseInt(v);
    form.post(route('handle-two-step-verification'))
}

</script>
<template>
    <form
        class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Two Step Verification</h1>
        <p class="opacity-70">We sent a verification code to your email. Enter the code from the email in the field
            below.</p>
        <p class="mt-6 mb-3">Type your 6 digit security code</p>
        <InputFieldOTP @entered="(v)=>submit(v)"/>
        <MessageSession
            class="mt-4"
            :status="status"
            :message="message"
        />
        <div class="flex items-center space-x-2 justify-center mt-6">
            <p>Didn't get the code?</p>
            <Link method="post" :href="route('two-step-verification')" class="text-[#7367F0]">Resend</Link>
        </div>
    </form>
</template>
