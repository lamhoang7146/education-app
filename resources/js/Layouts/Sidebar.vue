<script setup>
import {usePage} from '@inertiajs/vue3';
import {computed, ref} from "vue";
import {vAutoAnimate} from "@formkit/auto-animate";
import SidebarSubmenuLink from "../Components/SidebarSubmenuLink.vue";

defineProps({
    sidebar: Boolean
})
const emit = defineEmits(['emitSidebar'])
const component = computed(() => usePage().component)
const role = ref(false);
const user = ref(false);
</script>
<template>
    <div
        :class="{'translate-x-[0%]':sidebar}"
        class="w-[260px] xl:-translate-x-0 -translate-x-[100%] transition-transform duration-300 bg-content dark:dark-bg-content fixed top-0 left-0 bottom-0 z-50 shadow-xl pl-2 pr-1 py-4 grid grid-rows-[40px_1fr] gap-y-4">
        <div class="flex items-center ml-4 gap-x-2 h-10">
            <div class="text-2xl">
                Logo
            </div>
            <div class="text-2xl">
                Vue
            </div>
        </div>
        <ul class="overflow-y-auto sidebar pr-2">
            <Link
                :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Home'}"
                @click="emit('emitSidebar')"
                :href="route('home')"
                class=" flex justify-between items-center pl-4 pr-2 hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 rounded-md transition">
                <div class="flex items-center ">
                    <div class="mr-3">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <p>Home</p>
                </div>
            </Link>
            <SidebarSubmenuLink icon="fa-graduation-cap" name="Courses" reference="Courses" :component="component">
                <Link
                    :href="route('courses.index')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Courses/List'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>List
                </Link>
            </SidebarSubmenuLink>
            <SidebarSubmenuLink icon="fa-user" name="Users" reference="User" :component="component">
                <Link
                    :href="route('user.list')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'User/List'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>List
                </Link>
            </SidebarSubmenuLink>
            <SidebarSubmenuLink
                :component="component"
                reference="Role"
                name="Roles"
                icon="fa-lock"
            >
                <Link
                    :href="route('role.list')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Role/RoleList'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>List
                </Link>
            </SidebarSubmenuLink>
        </ul>
    </div>
</template>
