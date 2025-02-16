<script setup>
import LayoutAuth from "../../Layouts/LayoutAuth.vue";
import MessageSession from "../../Components/MessageSession.vue";
defineOptions({
    layout: LayoutAuth
});
defineProps({
    message:String,
    status:Boolean
})
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    email: null,
})
const submit = () => {
    form.post(route('forgot-password'),{
        onSuccess:()=>{
            form.reset();
        }
    })
}

</script>
<template>
    <form @submit.prevent="submit" class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Forgot Password?</h1>
        <p class="opacity-70">Enter your email and we'll send you instructions to reset your password</p>
        <div class="mt-6 space-y-6">
            <InputField
                label="Email"
                placeholder="abcxyz@gmail.com"
                v-model="form.email"
                :error="form.errors.email"
            />
        </div>
        <Button :disabled="form.processing" class="my-6 w-full">Send Reset Link</Button>
        <MessageSession class="mb-3" :status="status" :message="message" />
        <Link class="text-center block text-[#7367F0]" :href="route('login')"><span><</span><span>Back to login</span>
        </Link>
    </form>
</template>
