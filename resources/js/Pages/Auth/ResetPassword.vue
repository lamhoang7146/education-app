<script setup>
import LayoutAuth from "../../Layouts/LayoutAuth.vue";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import MessageSession from "../../Components/MessageSession.vue";
import {useForm} from "@inertiajs/vue3";
defineOptions({
    layout: LayoutAuth
});
const props = defineProps({
    token: String,
    email: String,
    message: String,
    status: Boolean,
})
const form = useForm({
    token:props.token,
    email:props.email,
    password:null,
    password_confirmation:null
})
const submit = ()=>{
    form.post(route('password.update'),{
        onError:()=>{
            form.reset('password','password_confirmation');
        }
    })
}


</script>
<template>
    <form @submit.prevent="submit" class="p-12 bg-content dark:dark-bg-content box-shadow-copy rounded-md w-[460px]">
        <h1 class="text-2xl font-bold text-center">Vuexy</h1>
        <h1 class="mt-6 mb-2 text-2xl font-medium">Reset Password</h1>
        <p class="opacity-70">Your new password must be different from previously used passwords</p>
        <div class="mt-6 space-y-6">
            <InputField
                label="New password"
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
            />
        </div>

        <Button class="my-6 w-full">Set New Password</Button>
        <MessageSession :status="status" :message="message" />
        <Link class="text-center block text-[#7367F0]" :href="route('login')"><span><</span><span>Back to login</span></Link>
    </form>
</template>
