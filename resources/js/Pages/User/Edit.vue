<script setup>
import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import {useForm} from "@inertiajs/vue3";
import Button from "../../Components/Button.vue";
import {computed, ref} from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
} from '@headlessui/vue';

const props = defineProps({
    roles: Object,
    user: Object
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
// TODO: Using reduce method to get object instead use array
const getRole = props.roles.filter(item => item.id === props.user.role_id)[0]
const getStatus = status.value.filter(item => item.id === props.user.status)[0]

const form = useForm({
    name: props.user.name,
    selectedRole: getRole,
    selectedStatus: getStatus,
    image: props.user.image,
    preview: null,
})
const chooseFile = e => {
    if (form.preview) {
        URL.revokeObjectURL(form.preview);
    }
    let file = e.target.files[0];
    form.preview = file ? URL.createObjectURL(e.target.files[0]) : null;
    form.image = file ?? null;
}
const url = computed(()=>{
    let getImage = null;
    if(form.image && form.preview){
        getImage = form.preview;
    }else if(form.image){
        getImage = `/storage/${form.image}`;
    }else{
        getImage = `/storage/default.webp`;
    }
    return getImage;
})

const submit = () => {
    form.post(route('user.update',{user:props.user.id}))
}

</script>
<template>
    <Container class="w-2/4 mx-auto">
        <h1 class="text-center font-medium text-xl">Edit User Information</h1>
        <p class="text-center mt-2 opacity-90">Editing user details will receive a privacy audit.</p>
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
                        :src="url"
                     alt=""/>
                </div>

                <p class="error mt-2">{{ form.errors.image }}</p>
            </div>
            <!-- End Upload Avatar -->

                <InputField
                    class="mb-6"
                    label="Username"
                    placeholder="Enter username"
                    type="text"
                    v-model="form.name"
                    :error="form.errors.name"
                />
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
