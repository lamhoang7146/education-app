<script setup>
import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import {useForm} from "@inertiajs/vue3";
import MessageSession from "../../Components/MessageSession.vue";
import {route} from "ziggy-js";
import {computed, ref, watch} from "vue";
import {debounce} from "lodash";

const props = defineProps({
    user:Object,
    message:String,
    status:Boolean
});
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

const formImage = useForm({
    image: props.user.image,
    preview: null
});
console.log(props.user.image)
const isChooseImage = ref(false);

const chooseFile = e => {
    isChooseImage.value = !isChooseImage.value;
    const file = e.target.files[0];
    if (formImage.preview) {
        URL.revokeObjectURL(formImage.preview);
    }
    formImage.preview = file ? URL.createObjectURL(file) : null;
    formImage.image = file ?? null;
}
const url = computed(() => {
    let getImage = null;
    if (formImage.preview) {
        getImage = formImage.preview;
    } else {
        getImage = formImage.image ? `/storage/${formImage.image}` : '/storage/default.webp';
    }
    return getImage;
})
watch(isChooseImage, debounce(
    () => formImage.post(route('profile.update.avatar'), {
        image: formImage.image
    }), 100
))

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
        <div class="mx-auto">
            <div
                class="mx-auto relative w-48 h-48 rounded-full overflow-hidden border border-slate-300"
            >
                <label for="avatar" class="absolute inset-0 grid content-end cursor-pointer">
                    <span class="bg-white/70 pb-2 text-center">Image</span>
                </label>
                <input type="file" @input="chooseFile" id="avatar" hidden/>
                <img
                    class="object-cover w-full h-full"
                    :src="url"
                />
            </div>
        </div>
        <p class="mt-4 text-red-500">{{formImage.errors.image}}</p>
        <!-- End Upload Avatar -->
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
