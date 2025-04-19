<script setup>
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import LayoutAuth from "../../Layouts/LayoutAuth.vue";
import {useForm} from "@inertiajs/vue3";
import {vAutoAnimate} from "@formkit/auto-animate";
import MessageSession from "../../Components/MessageSession.vue";
import {route} from "ziggy-js";

defineOptions({
    layout: LayoutAuth
});
defineProps({
    verified: Boolean,
    message: String,
    status: Boolean,
})

const form = useForm({
    email: null,
    password: null,
    remember: null,
});
const submit = () => {
    form.post(route('login'), {
        onError: () => {
            form.reset('password');
        }
    })
}
</script>
<template>
    <form @submit.prevent="submit" class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Welcome to vuexy!</h1>
        <p class="opacity-70">Please sign-in to your account and start the adventure</p>
        <div class="mt-6 space-y-6">
            <InputField
                label="Email"
                placeholder="abcxyz@gmail.com"
                v-model="form.email"
                :error="form.errors.email"
            />
            <div v-auto-animate>
                <div v-if="verified" class="flex items-center space-x-2 justify-center">
                    <p>Your account is not verify?</p>
                    <Link :href="route('form-verify-email')" class="text-[#7367F0]">Click here</Link>
                </div>
            </div>
            <InputField
                label="Password"
                placeholder="············"
                type="password"
                v-model="form.password"
                :error="form.errors.password"
            />
        </div>
        <div class="my-6 flex items-center justify-between">
            <div>
                <input v-model="form.remember"
                       class="scale-150 translate-y-[2px] accent-[#7367F0] cursor-pointer mr-2 ml-2" type="checkbox"
                       name=""
                       id="checkbox">
                <label for="checkbox" class="cursor-pointer">Remember me</label>
            </div>
            <Link :href="route('forgot-password')" class="text-[#7367F0]">Forgot password?</Link>
        </div>
        <Button class="w-full" :disabled="form.processing">Login</Button>
        <MessageSession
            class="mt-4"
            :status="status"
            :message="message"
        />
        <div class="flex items-center space-x-2 justify-center mt-6">
            <p>New on our platform?</p>
            <Link :href="route('register')" class="text-[#7367F0]">Create an account</Link>
        </div>
<!--        <div class="flex items-center space-x-4 my-4">-->
<!--            <div class="w-full h-[.5px] bg-gray-200"></div>-->
<!--            <span>or</span>-->
<!--            <div class="w-full h-[.5px] bg-gray-200"></div>-->
<!--        </div>-->
<!--        <Link :href="route('google-auth')" class="text-center bg-red-400 w-full text-white py-2 rounded-md font-medium block space-x-2">-->
<!--            <span><i class="fa-brands fa-google"></i></span><span>Sign in with Google</span></Link>-->

    </form>
</template>
