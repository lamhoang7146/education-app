<script setup>
import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import {useForm} from "@inertiajs/vue3";
import MessageSession from "../../Components/MessageSession.vue";
import {route} from "ziggy-js";

const props = defineProps({
    user:Object,
    message:String,
    status:Boolean
});
console.log(props.user)
const formProfile = useForm({
    name: props.user.name,
    email: props.user.email,
})

const formPassword = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
})

const handleSubmitProfile = () => {
    formProfile.post(route('profile.update'), {
        onSuccess: () => {
            formProfile.clearErrors();
            formProfile.processing = false;
        },
    });
}
const handleSubmitPassword = () => {
    formPassword.post(route('profile.update.password'), {
        onSuccess: () => {
            formPassword.reset();
            formPassword.clearErrors();
            formPassword.processing = false;
        },
    });
}

</script>
<template>
    <MessageSession
    :message="message"
    :status="status"
    class="mb-4"
    />
    <Container class="grid grid-cols-2">
        <form @submit.prevent="handleSubmitProfile" class="space-y-3">
            <h1 class="text-lg font-medium">Edit information</h1>

            <InputField v-model="formProfile.name" :error="formProfile.errors.name" label="Name" type="text" placeholder="Enter your name" />
            <InputField v-model="formProfile.email" disabled label="Email" type="email" />
            <div class="text-right">
                <Button class="px-4">Update profile</Button>
            </div>
        </form>
        <div class="w-48 h-48 mx-auto rounded-full overflow-hidden">
            <img class="w-full h-full mx-auto" :src="user?.image ? `/storage/${user.image}` : '/storage/default.webp'" alt="">
        </div>
    </Container>
    <Container class="grid grid-cols-2 my-4">
        <form @submit.prevent="handleSubmitPassword" class="space-y-3">
            <h1 class="text-lg font-medium">Change password</h1>
            <InputField v-model="formPassword.current_password" :error="formPassword.errors.current_password" label="Current password" type="password" placeholder="Enter your current password" />
            <InputField v-model="formPassword.password" :error="formPassword.errors.password" label="New password" type="password" placeholder="Enter your new password" />
            <InputField v-model="formPassword.password_confirmation" label="Confirm password" type="password" placeholder="Confirm password" />
            <div class="text-right">
                <Button class="px-4">Update password</Button>
            </div>
        </form>
    </Container>
</template>
