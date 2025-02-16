<script setup>
import {ref} from "vue";
import LayoutAuth from "../../Layouts/LayoutAuth.vue";
import {useForm} from "@inertiajs/vue3";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
defineOptions({
    layout: LayoutAuth
});
const isAgree = ref(false)
const form = useForm({
    name:null,
    email:null,
    password:null,
    password_confirmation:null
});
const handleSubmit = ()=>{
    form.post(route('register'),{
        onError:()=>{
            form.reset('password','password_confirmation');
        }
    })
}

</script>
<template>
    <form @submit.prevent="handleSubmit" class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Adventure starts here</h1>
        <p class="opacity-70">Make your app management easy and fun!</p>
        <div class="mt-6 space-y-6">
            <InputField
                label="Username"
                placeholder="Lam Hoang"
                v-model="form.name"
                :error="form.errors.name"
            />
            <InputField
                label="Email"
                placeholder="abcxyz@gmail.com"
                v-model="form.email"
                :error="form.errors.email"
            />
            <InputField
                label="Password"
                placeholder="············"
                type="password"
                v-model="form.password"
                :error="form.errors.password"
            />
            <InputField
                label="Confirm password"
                placeholder="············"
                type="password"
                v-model="form.password_confirmation"
                :error="form.errors.password_confirmation"
            />
        </div>
        <div class="my-6 flex items-center space-x-1">
            <input @click="isAgree = !isAgree" class="scale-150 translate-y-[2px] accent-[#7367F0] cursor-pointer mr-2 ml-2" type="checkbox" name=""
                   id="checkbox">
            <label for="checkbox" class="cursor-pointer">I agree to <span
                class="text-[#7367F0]">privacy policy & terms</span></label>
        </div>
        <Button class="w-full" :disabled="!isAgree || form.processing">Sign Up</Button>
        <div class="flex items-center space-x-2 justify-center mt-6">
            <p>Already have an account?</p>
            <Link :href="route('login')" class="text-[#7367F0]">Sign in instead</Link>
        </div>
    </form>
</template>
