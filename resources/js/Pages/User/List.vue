<script setup>
import {usePage} from '@inertiajs/vue3';
const permissions = usePage().props.auth.user?.permissions;
import {router, useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js"
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import MessageSession from "../../Components/MessageSession.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import {computed, ref} from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    MenuButton,
    Menu,
    MenuItems
} from '@headlessui/vue';
import Modal from "../../Components/Modal.vue";
import InputField from "../../Components/InputField.vue";

const params = route().params;
const props = defineProps({
    roles: Object,
    users: Object,
    search: String,
    message: String,
    status: Boolean
})

let roles = props.roles;

const statuses = [
    {
        id: 0,
        name: 'Suspended'
    },
    {
        id: 1,
        name: 'Active'
    }
];

const getRoleParam = roles.filter(item => {
    return item.id === parseInt(params?.selectedRole?.id)
});

const getStatusParam = statuses.filter(item => {
    return item.id === parseInt(params?.selectedStatus?.id)
});

let selectedRole = ref(getRoleParam[0]);
let selectedStatus = ref(getStatusParam[0]);
let username = ref(props.search);
const resetSelected = () => {
    selectedStatus.value = null;
    selectedRole.value = null;
    username.value = null;
    router.get(route('user.list'), {}, {
        preserveScroll: true
    })
}

const isModal = ref(false);
const infoModal = ref(null);
const showModel = (id) => {
    isModal.value = true;
    infoModal.value = props.users.data.filter(item => item.id === id)[0];
}
const removeModel = () => {
    isModal.value = false;
    infoModal.value = null
}
const changeStatus = () => {
    isModal.value = false;
    router.post(route('user.status', {user: infoModal.value.id}), {}, {
        preserveScroll: true
    })
}
const isClose = ref(false)
const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    selectedRole: roles[0],
    selectedStatus: statuses[0],
    image: null,
    preview: null
})
const removeAdd = ()=>{
    isClose.value = false;
    form.reset('password','password_confirmation')
}
const chooseFile = e => {
    if (form.preview) {
        URL.revokeObjectURL(form.preview);
    }
    let file = e.target.files[0];
    form.preview = file ? URL.createObjectURL(e.target.files[0]) : null;
    form.image = file ?? null;
}


const submit = () => {
    form.post(route('user.store'), {
        onError: () => {
            form.reset('password_confirmation');
        },
        onSuccess: () => {
            form.reset()
            isClose.value = false;
        },
        preserveScroll:true,
    })
}

const isCloseEdit = ref(false)

const formEdit = useForm({
    id: null,
    name: null,
    email: null,
    selectedRole: null,
    selectedStatus: null,
    image: null,
    preview: null,
})
const showEdit = id => {
    isCloseEdit.value = true;
    const user = props.users.data.find(item => item.id === id);
    formEdit.id = user.id;
    formEdit.name = user.name;
    formEdit.email = user.email;
    formEdit.selectedRole = props.roles.find(item => item.id === user?.get_role?.id);
    formEdit.selectedStatus = statuses.find(item => item.id === user?.status);
    formEdit.image = user.image;
}
const chooseFileEdit = e => {
    if (formEdit.preview) {
        URL.revokeObjectURL(formEdit.preview);
    }
    let file = e.target.files[0];
    formEdit.preview = file ? URL.createObjectURL(e.target.files[0]) : null;
    formEdit.image = file ?? null;
}
const url = computed(() => {
    let getImage = null;
    if (formEdit.image && formEdit.preview) {
        getImage = formEdit.preview;
    } else if (formEdit.image) {
        getImage = `/storage/${formEdit.image}`;
    } else {
        getImage = `/storage/default.webp`;
    }
    return getImage;
})


const removeShowEdit = () => {
    isCloseEdit.value = false
    formEdit.reset();
}
const update = () => {
    formEdit.post(route('user.update', {user: formEdit.id}),{
        preserveScroll:true,
        onSuccess: () => {
            isCloseEdit.value = false
        },
        onFinish:()=>{
            isCloseEdit.value = false
        }
    })

}
</script>
<template>
    <Modal :is-open="isClose" @close="removeAdd">
        <div @click.stop
             class="py-16 main h-[90%] w-[500px] px-12 bg-content dark:dark-bg-content rounded-md box-shadow-copy overflow-auto"
        >
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
                            alt=""/>
                    </div>

                    <p class="error mt-2">{{ form.errors.image }}</p>
                </div>
                <!-- End Upload Avatar -->

                <InputField
                    class="mb-4"
                    label="Username"
                    placeholder="Enter username"
                    type="text"
                    v-model="form.name"
                    :error="form.errors.name"
                />
                <InputField
                    class="mb-4"
                    label="Email"
                    placeholder="Enter email"
                    type="email"
                    v-model="form.email"
                    :error="form.errors.email"
                />
                <InputField
                    class="mb-4"
                    label="Password"
                    placeholder="Enter password"
                    type="password"
                    v-model="form.password"
                    :error="form.errors.password"
                />
                <InputField
                    class="mb-4"
                    label="Confirm password"
                    placeholder="Enter password"
                    type="password"
                    v-model="form.password_confirmation"
                />
                <Listbox v-model="form.selectedRole" class="mb-2">
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
                        <h1 class="mb-2 ml-1 font-medium">Select status</h1>
                        <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                            {{ form.selectedStatus?.name || 'Select role' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:da    rk-bg-behind rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="role in statuses"
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
                    <Button :disabled="form.processing" class="px-4">Add</Button>
                    <span
                        @click="removeAdd"
                        class="cursor-pointer rounded-md font-medium bg-zinc-100 hover:bg-zinc-200 transition duration-300 text-gray-500 py-[9.5px] pl-3 pr-4 ml-4 ">
                        Cancel
                    </span>
                </div>
            </form>
        </div>
    </Modal>
    <Modal :is-open="isCloseEdit" @close="removeShowEdit">
        <div @click.stop
             class="py-16 main h-[90%] w-[500px] px-12 bg-content dark:dark-bg-content rounded-md box-shadow-copy overflow-auto"
        >
            <h1 class="text-center font-medium text-xl">Edit User Information</h1>
            <p class="text-center mt-2 opacity-90">Editing user details will receive a privacy audit.</p>
            <form @submit.prevent="update" class="mt-4">
                <!-- Upload Avatar -->
                <div class="grid place-items-center">
                    <div
                        class="relative w-40 h-40 rounded-full overflow-hidden border border-slate-300"
                    >
                        <label for="avatar2" class="absolute inset-0 grid content-end cursor-pointer">
                            <span class="bg-white/70 pb-2 text-center">Image</span>
                        </label>
                        <input type="file" @input="chooseFileEdit" id="avatar2" hidden/>

                        <img
                            class="object-cover w-40 h-40"
                            :src="url"
                            alt=""/>
                    </div>

                    <p class="error mt-2">{{ form.errors.image }}</p>
                </div>
                <!-- End Upload Avatar -->

                <InputField
                    class="mb-4"
                    label="Username"
                    placeholder="Enter username"
                    type="text"
                    v-model="formEdit.name"
                    :error="formEdit.errors.name"
                />
                <InputField
                    class="mb-4"
                    label="Email"
                    placeholder="Enter email"
                    type="email"
                    :disabled="true"
                    v-model="formEdit.email"
                />
                <Listbox v-model="formEdit.selectedRole" class="mb-2">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 font-medium">Select role</h1>
                        <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                            {{ formEdit.selectedRole?.name || 'Select role' }}
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
                <Listbox v-model="formEdit.selectedStatus" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 font-medium">Select status</h1>
                        <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                            {{ formEdit.selectedStatus?.name || 'Select role' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:da    rk-bg-behind rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="role in statuses"
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
                    <Button :disabled="form.processing" class="px-4">Update</Button>
                    <span
                        @click="removeShowEdit"
                        class="cursor-pointer rounded-md font-medium bg-zinc-100 hover:bg-zinc-200 transition duration-300 text-gray-500 py-[9.5px] pl-3 pr-4 ml-4 ">
                        Cancel
                    </span>
                </div>
            </form>
        </div>
    </Modal>

    <Modal :is-open="isModal" @close="isModal = false">
        <Container @click.stop class="max-w-[500px]">
            <h1 class="text-lg font-medium">Are you sure you want to change the {{ infoModal.name }}'s status</h1>
            <p class="text-sm mt-2 mb-4">Please confirm your action. This will update the <span
                class="font-medium">{{ infoModal.name }}</span>'s status, and an email notification will be sent to the
                user.</p>
            <div class="space-x-2">
                <button
                    @click="changeStatus"
                    class="text-center bg-[#7367F0] text-sm text-white py-2 px-4 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition">
                    Confirm
                </button>
                <button
                    @click="removeModel"
                    class="text-center bg-red-500 text-sm text-white py-2 px-4 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition">
                    Cancel
                </button>
            </div>
        </Container>
    </Modal>
    <Container class="mb-3">
        <div class="flex justify-between items-center">
            <h1 class="text-lg font-medium">Filters</h1>
            <div
                v-if="permissions?.includes('Add user')"
                @click="isClose = true"
                class="text-center bg-[#7367F0] text-white py-2 rounded-md outline-0 font-medium cursor-pointer transition px-4 flex items-center">
                <i class="fa-solid fa-plus text-sm font-light mr-2"></i>
                Add new user
            </div>
        </div>
        <div class="mt-4 grid grid-cols-4 gap-x-4">
            <div>
                <h1 class="mb-2 ml-1 font-medium">Search user or email</h1>
                <input v-model="username"
                       class="py-2 w-full outline-0 border-[1px] order-gray-200 rounded-md px-4 placeholder:text-primary placeholder:dark:dark-text-primary bg-transparent"
                       type="text" placeholder="Search...">
            </div>
            <Listbox v-model="selectedRole">
                <div class="relative">
                    <h1 class="mb-2 ml-1 font-medium">Select role</h1>
                    <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                        {{ selectedRole?.name || 'Select role' }}
                    </ListboxButton>
                    <transition name="list">
                        <ListboxOptions
                            class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full">
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
            <Listbox v-model="selectedStatus">
                <div class="relative">
                    <h1 class="mb-2 ml-1 font-medium">Select status</h1>
                    <ListboxButton class=" border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full">
                        {{ selectedStatus?.name || 'Select status' }}
                    </ListboxButton>
                    <transition name="list">
                        <ListboxOptions
                            class="absolute top-[120%]  bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full">
                            <ListboxOption
                                class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                v-for="status in statuses"
                                :key="status.id"
                                :value="status"
                            >
                                {{ status.name }}
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
            <div>
                <h1 class="mb-2 ml-1 font-medium">Filter & Remove filter</h1>
                <div class="w-full grid grid-cols-2 gap-x-2">
                    <Link
                        preserveScroll
                        class="text-center bg-[#7367F0] text-white py-2 rounded-md outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition"
                        :href="route('user.list',{selectedRole,selectedStatus,username})">Filter
                    </Link>
                    <div>
                        <Button @click="resetSelected" class="bg-red-500 w-full">Remove</Button>
                    </div>
                </div>
            </div>
        </div>

    </Container>

        <MessageSession
            :message="message"
            :status="status"
        />

    <Container class="mt-3" v-if="users.data.length > 0">
        <table class="w-full">
            <thead>
            <tr class="grid grid-cols-[50px_2fr_1fr_1fr_1fr] w-full text-sm font-medium  border-b-[1px] border-gray-200 pb-5">
                <td>
                    ID
                </td>
                <td>
                    USER
                </td>
                <td>
                    ROLE
                </td>
                <td>STATUS</td>
                <td>ACTIONS</td>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="user in users.data"
                class="grid grid-cols-[50px_2fr_1fr_1fr_1fr] w-full text-sm py-2 border-b-[1px] border-gray-200">
                <td class="flex items-center">
                    <!--                    <input class="scale-[1.3] translate-y-[2px] cursor-pointer accent-[#8278F2] " type="checkbox">-->
                    {{ user.id }}
                </td>
                <td class="grid grid-cols-[50px_1fr] gap-x-2">
                    <div>
                        <img class="h-12 w-12 object-center rounded-full"
                             :src="user.image ? `/storage/${user.image}` : '/storage/default.webp'" alt="">
                    </div>
                    <div class="flex flex-col justify-center">
                        <h1 class="font-medium">{{ user.name }}</h1>
                        <p>{{ user.email }}</p>
                    </div>
                </td>
                <td class="flex items-center">
                    {{ user.get_role.name }}
                </td>
                <td class="flex items-center font-medium cursor-default">
                    <span class="pt-[3px] pb-[5px] px-3 rounded-md bg-green-100 text-green-500" v-if="user.status">Active</span>
                    <span class="pt-[3px] pb-[5px] px-3 rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
                </td>
                <Menu as="span" class="relative flex items-center">
                    <MenuButton
                        v-if="permissions?.includes('Edit user')"
                        class="ml-4 rounded-full h-8 w-8 flex items-center justify-center cursor-pointer hover:hover-selected dark:hover:dark-hover-selected transition duration-300">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </MenuButton>
                    <transition enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                        <MenuItems
                            class="absolute top-[80%] right-[85%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-3/4 z-30"
                        >
                            <span
                                @click="showEdit(user.id)"
                                class="block cursor-pointer text-left p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md w-full font-medium">
                                Edit
                            </span>
                            <span
                                @click="showModel(user.id)"
                                class="block cursor-pointer text-left p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md w-full font-medium">{{
                                    user?.status ? 'Suspended' : 'Activate'
                                }}</span>
                        </MenuItems>
                    </transition>
                </Menu>
            </tr>

            </tbody>
        </table>
    </Container>
    <Container class="mt-6" v-else>
        <h1>There are no user!</h1>
    </Container>
    <PaginationLinks
        class="my-4"
        :paginator="users"
    />
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
