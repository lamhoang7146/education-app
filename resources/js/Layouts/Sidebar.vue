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
            <li>
                <div
                    @click="user = !user"
                    :class="{'hover-selected dark:dark-hover-selected':component.includes('User')}"
                    class="flex justify-between items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md cursor-pointer">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <p>User</p>
                    </div>
                    <div>
                        <i
                            :class="user ? 'transform rotate-90' : 'transform rotate-0'"
                            class="fa-solid fa-angle-right transition"></i>
                    </div>
                </div>
                <ul v-auto-animate>
                    <div v-if="user">
                        <Link
                            :href="route('user.show')"
                            @click="emit('emitSidebar')"
                            :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'User/Show'}"
                            class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                            <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>Add
                        </Link>
                        <Link
                            :href="route('user.list')"
                            @click="emit('emitSidebar')"
                            :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'User/List'}"
                            class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                            <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>List
                        </Link>
                    </div>
                </ul>
            </li>

            <li>
                <div
                    @click="role = !role"
                    :class="{'hover-selected dark:dark-hover-selected':component.includes('Role')}"
                    class="flex justify-between items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md cursor-pointer">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <p>Roles</p>
                    </div>
                    <div>
                        <i
                            :class="role ? 'transform rotate-90' : 'transform rotate-0'"
                            class="fa-solid fa-angle-right transition"></i>
                    </div>
                </div>
                <ul v-auto-animate>
                    <div v-if="role">
                        <Link
                            :href="route('role.list')"
                            @click="emit('emitSidebar')"
                            :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Role/RoleList'}"
                            class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                            <span><i class="fa-regular fa-circle text-xs mr-2"></i></span>List
                        </Link>
                    </div>
                </ul>
            </li>

<!--            <SidebarSubmenuLink-->
<!--                icon="lock"-->
<!--                nameParent="Role"-->
<!--                :component="component"-->
<!--                :routeList="[{-->
<!--             ziggy: 'role.show',-->
<!--             component:'Role/RoleAdd',-->
<!--             name:'Add role'-->
<!--            },-->
<!--            {-->
<!--             ziggy: 'role.list',-->
<!--             component:'Role/RoleList',-->
<!--             name:'List role'-->
<!--            }-->
<!--            ]"-->
<!--            />-->
        </ul>
    </div>
</template>
