<script setup>
import {router} from "@inertiajs/vue3";

const params = route().params;
const props = defineProps({
    roles: Object,
    users: Object,
    search: String,
    message: String,
    status: Boolean
})
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import MessageSession from "../../Components/MessageSession.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import {ref} from 'vue'
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    MenuButton,
    Menu,
    MenuItems
} from '@headlessui/vue'

let roles = [{id: null, name: 'Select role'}, ...props.roles];

const statuses = [
    {
        id: null,
        name: 'Select status'
    },
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
    router.get(route('user.list'), {

    }, {
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
    router.post(route('user.status', {user: infoModal.value.id}), {}, {
        preserveScroll: true
    })
}
</script>
<template>
    <div @click="removeModel" v-if="isModal" class="fixed inset-0 flex items-center justify-center bg-[#00000059] z-50">
        <Container class="max-w-[500px]">
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
    </div>
    <Container class="mb-3">
        <h1 class="text-lg font-medium">Filters</h1>
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
    <Container v-if="message">
        <MessageSession
            class="mb-0"
            :message="message"
            :status="status"
        />
    </Container>
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
                    {{user.id}}
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
                            <Link
                                :href="route('user.edit',{id: user.id})"
                                as="button"
                                class="block cursor-pointer text-left p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md w-full font-medium">
                                Edit
                            </Link>
                            <span
                                @click="showModel(user.id)"
                                class="block cursor-pointer text-left p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md w-full font-medium">{{ user?.status ? 'Suspended' : 'Activate' }}</span>
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
