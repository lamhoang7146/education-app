<script setup>
import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import {useForm} from "@inertiajs/vue3";
import Button from "../../Components/Button.vue";
import {ref} from "vue";
import {
    Listbox,
    ListboxLabel,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue';
const props = defineProps({
    roles: Object
})
const status = ref([
    {
        id: 0,
        name: 'Suspended'
    },
    {
        id: 1,
        name: 'Active'
    }
])
const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    selectedRole: props.roles[0],
    selectedStatus: status.value[0],
    image: null,
    preview: null
})
const chooseFile = e => {
    if (form.preview) {
        URL.revokeObjectURL(form.preview);
    }
    let file = e.target.files[0];
    form.preview = file ? URL.createObjectURL(e.target.files[0]) : null;
    form.image = file ?? null;
}


const submit = () => {
    form.post(route('user.store'),{
        onError:()=>{
            form.reset('password_confirmation');
        }
    })
}

</script>
<template>
    <Container>
        <h1 class="text-center font-medium text-xl">Add User Information</h1>
        <p class="text-center mt-2 opacity-90">Adding user details will receive a privacy audit.</p>
        <form @submit.prevent="submit" class="mt-4">
            <!-- Upload Avatar -->
            <div class="grid place-items-center">
                <div
                    class="relative w-40 h-40 rounded-full overflow-hidden border border-slate-300"
                >
                    <label for="avatar" class="absolute inset-0 grid content-end cursor-pointer">
                        <span class="bg-white/70 pb-2 text-center">Image</span>
                    </label>
                    <input type="file" @input="chooseFile" id="avatar" hidden/>

                    <img
                        class="object-cover w-40 h-40"
                        :src="form.preview ?? '/storage/default.webp'"
                    />
                </div>

                <p class="error mt-2">{{ form.errors.image }}</p>
            </div>
            <!-- End Upload Avatar -->
            <div class="grid grid-cols-2 gap-x-6">
                <InputField
                    label="Username"
                    placeholder="Enter username"
                    type="text"
                    v-model="form.name"
                    :error="form.errors.name"
                />
                <InputField
                    label="Email"
                    placeholder="Enter email"
                    type="email"
                    v-model="form.email"
                    :error="form.errors.email"
                />
            </div>
            <div class="grid grid-cols-2 gap-x-6 my-6">
                <InputField
                    label="Password"
                    placeholder="Enter password"
                    type="password"
                    v-model="form.password"
                    :error="form.errors.password"
                />
                <InputField
                    label="Confirm password"
                    placeholder="Enter password"
                    type="password"
                    v-model="form.password_confirmation"
                />
            </div>
            <div class="grid grid-cols-2 gap-x-6">
<!--                <Listbox v-model="form.selectedRole">-->
<!--                    <div class="relative mt-1">-->
<!--                        <ListboxLabel class="mb-2 text-[14px]">Select role</ListboxLabel>-->
<!--                        <ListboxButton-->
<!--                            class="dark:bg-transparent  dark:border-[1px] relative w-full cursor-pointer rounded-lg bg-white py-2 pl-3 pr-10 text-left shadow-md focus:outline-none focus-visible:border-indigo-500 focus-visible:ring-2 focus-visible:ring-white/75 focus-visible:ring-offset-2 focus-visible:ring-offset-[#7367F0] sm:text-sm">-->
<!--                            <span class="block truncate">{{ form.selectedRole.name }}</span>-->
<!--                            <span-->
<!--                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"></span>-->
<!--                        </ListboxButton>-->

<!--                        <transition-->
<!--                            leave-active-class="transition duration-100 ease-in"-->
<!--                            leave-from-class="opacity-100"-->
<!--                            leave-to-class="opacity-0"-->
<!--                        >-->
<!--                            <ListboxOptions-->
<!--                                class="absolute mt-2 max-h-60 w-full overflow-auto rounded-md bg-white  text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"-->
<!--                            >-->
<!--                                <ListboxOption-->
<!--                                    v-slot="{ active, selected }"-->
<!--                                    v-for="role in roles"-->
<!--                                    :key="role.name"-->
<!--                                    :value="role">-->
<!--                                    <li-->
<!--                                        class="cursor-pointer"-->
<!--                                        :class="[-->
<!--                                            active ? 'bg-[#7367F0] text-white' : 'text-gray-900',-->
<!--                                               'relative cursor-default select-none py-2 pl-10 pr-4']">-->
<!--                <span-->
<!--                    :class="[-->
<!--                    selected ? 'font-medium' : 'font-normal',-->
<!--                    'block truncate'-->
<!--                  ]"-->
<!--                >{{ role.name }}</span>-->
<!--                                        <span-->
<!--                                            v-if="selected"-->
<!--                                            class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-600">-->
<!--                </span>-->
<!--                                    </li>-->
<!--                                </ListboxOption>-->
<!--                            </ListboxOptions>-->
<!--                        </transition>-->
<!--                    </div>-->
<!--                </Listbox>-->
                <Listbox v-model="form.selectedRole" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 font-medium">Select role</h1>
                        <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                            {{ form.selectedRole?.name || 'Select role' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full z-30">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role"
                                >
                                    {{ role.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
                <Listbox v-model="form.selectedStatus" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 font-medium">Select role</h1>
                        <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                            {{ form.selectedStatus?.name || 'Select role' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="role in status"
                                    :key="role.id"
                                    :value="role"
                                >
                                    {{ role.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
            <div class="text-center mt-5">
                <Button :disabled="form.processing" class="px-4">Submit</Button>
                <Link
                    :href="route('user.list')"
                    class="rounded-md font-medium bg-zinc-100 hover:bg-zinc-200 transition duration-300 text-gray-500 py-[9.5px] pl-3 pr-4 ml-4 ">
                    Cancel
                </Link>
            </div>
        </form>
    </Container>
</template>
<style scoped>
.list-enter-from, .list-leave-to {
    transform: translateY(-10px);
    opacity: 0;
}

.list-enter-to, .list-leave-from {
    transform: translateY(0px);
    opacity: 1;
}

.list-enter-active, .list-leave-active {
    transition: .2s ease-in-out;
}


</style>
