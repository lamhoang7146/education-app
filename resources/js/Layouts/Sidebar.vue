<script setup>
import {usePage} from '@inertiajs/vue3';
import {computed} from "vue";
import SidebarSubmenuLink from "../Components/SidebarSubmenuLink.vue";
import {route} from "ziggy-js";

defineProps({
    sidebar: Boolean
})
const emit = defineEmits(['emitSidebar'])
const component = computed(() => usePage().component)
const user = computed(()=> usePage().props.auth.user)
const permissions = usePage().props?.auth?.user?.permissions;
</script>

<template>
    <div
        :class="{'translate-x-[0%]':sidebar}"
        class="w-[260px] xl:-translate-x-0 -translate-x-[100%] transition-transform duration-300 bg-content dark:dark-bg-content fixed top-0 left-0 bottom-0 z-50 shadow-xl pl-2 pr-1 py-4 grid grid-rows-[40px_1fr] gap-y-4">
        <div class="flex items-center ml-1 gap-x-2 h-10">
            <div>
                <svg width="35" height="24" viewBox="0 0 34 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path class="text-[#8278F2]" fill-rule="evenodd" clip-rule="evenodd" d="M0.00183571 0.3125V7.59485C0.00183571 7.59485 -0.141502 9.88783 2.10473 11.8288L14.5469 23.6837L21.0172 23.6005L19.9794 10.8126L17.5261 7.93369L9.81536 0.3125H0.00183571Z" fill="currentColor"></path>
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.17969 17.7762L13.3027 3.75173L17.589 8.02192L8.17969 17.7762Z" fill="#161616"></path>
                    <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.58203 17.2248L14.8129 5.24231L17.6211 8.05247L8.58203 17.2248Z" fill="#161616"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.25781 17.6914L25.1339 0.3125H33.9991V7.62657C33.9991 7.62657 33.8144 10.0645 32.5743 11.3686L21.0179 23.6875H14.5487L8.25781 17.6914Z" fill="currentColor"></path>
                </svg>
            </div>
            <p class="font-medium">Community of the galaxy</p>
        </div>
        <ul class="overflow-y-auto main pr-2">
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
            <SidebarSubmenuLink icon="fa-graduation-cap" name="Courses" reference="Courses/List" :component="component">
                <Link
                    :href="route('courses.index')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Courses/List'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-list-ul text-sm mr-2"></i></span>List
                </Link>
                <Link
                    v-if="user"
                    :href="route('courses.purchased')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Courses/Purchased'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-cart-shopping text-sm mr-2"></i></span>Purchased course
                </Link>
                <Link
                    v-if="user"
                    :href="route('courses.find-ai')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Courses/CoursesAI'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-robot text-sm mr-2"></i></span>Find course by AI
                </Link>
            </SidebarSubmenuLink>
            <SidebarSubmenuLink v-if="user && permissions?.includes('Courses management')" icon="fa-bars-progress" name="Courses management" reference="CoursesManagement" :component="component">
                <Link
                    v-if="permissions?.includes('Courses category')"
                    :href="route('courses.management.category')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'CoursesManagementCategory/Category'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-list-ul text-sm mr-2"></i></span>Category
                </Link>
                <Link
                    v-if="permissions?.includes('Courses')"
                    :href="route('courses.management.courses')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'CoursesManagementCourses/Courses'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-list-ul text-sm mr-2"></i></span>Courses
                </Link>

            </SidebarSubmenuLink>
            <SidebarSubmenuLink v-if="user && permissions?.includes('User')" icon="fa-user" name="Users" reference="User" :component="component">
                <Link
                    v-if="permissions?.includes('User')"
                    :href="route('user.list')"
                    @click="emit('emitSidebar')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'User/List'}"
                    class="flex items-center hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 pl-4 pr-2 rounded-md transition">
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-list-ul text-sm mr-2"></i></span>List
                </Link>
            </SidebarSubmenuLink>
            <SidebarSubmenuLink
                v-if="user && permissions.includes('Role')"
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
                    <span class="-translate-y-[2px]"><i class="fa-solid fa-list-ul text-sm mr-2"></i></span>List
                </Link>
            </SidebarSubmenuLink>

            <SidebarSubmenuLink v-if="user && permissions?.includes('Analytics')" icon="fa-chart-pie" name="Analytics" reference="Analytics" :component="component">
                <Link
                    v-if="user && permissions?.includes('Analytics')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'Analytics'}"
                    @click="emit('emitSidebar')"
                    :href="route('analytics')"
                    class=" flex justify-between items-center pl-6 pr-2 hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 rounded-md transition">
                    <div class="flex items-center ">
                        <div class="mr-3 -translate-y-[2px]">
                            <i class="fa-solid fa-chart-simple text-sm"></i>
                        </div>
                        <p>Analytics</p>
                    </div>
                </Link>

                <Link
                    v-if="user && permissions?.includes('AI Analytics')"
                    :class="{'dark-selected hover:dark-selected dark:hover:dark-selected':component === 'AiAnalytics'}"
                    @click="emit('emitSidebar')"
                    :href="route('ai.analytics')"
                    class=" flex justify-between items-center pl-5 pr-2 hover:hover-selected dark:hover:dark-hover-selected py-2 mb-2 rounded-md transition">
                    <div class="flex items-center ">
                        <div class="mr-3 -translate-y-[2px]">
                            <i class="fa-solid fa-robot text-sm"></i>
                        </div>
                        <p>AI analytics</p>
                    </div>
                </Link>
            </SidebarSubmenuLink>


        </ul>
    </div>
</template>
