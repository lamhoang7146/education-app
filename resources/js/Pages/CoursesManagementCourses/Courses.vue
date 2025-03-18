<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import {computed, ref, watch} from "vue";
import {Listbox, ListboxButton, ListboxOption, ListboxOptions, Switch} from "@headlessui/vue";
import InputField from "../../Components/InputField.vue";
import Modal from "../../Components/Modal.vue";
import {router, useForm} from "@inertiajs/vue3";
import {vAutoAnimate} from "@formkit/auto-animate";
import Tiptap from "../../Components/Tiptap.vue";
import {route} from "ziggy-js";
const params = route().params;
import MessageSession from "../../Components/MessageSession.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
}

const props = defineProps({
    category_courses: Object,
    courses: Object,
    message: String,
    status: Boolean
})

let selectedCategory = ref(props.category_courses.find(item=> item.id === parseInt(params.category_courses_id)));
const categoryCoursesParams = ref([{id:null,name:'Select category'},...props.category_courses]);
watch(selectedCategory, (newCategory) => {
    router.get(route('courses.management.courses'), {
        category_courses_id: newCategory ? newCategory.id : null,
    })
})

const levels = [
    {
        id: 0,
        name: 'Easy'
    },
    {
        id: 1,
        name: 'Medium'
    },
    {
        id: 2,
        name: 'Hard'
    },
    {
        id: 3,
        name: 'Extremely'
    }
];

const statusAddCourses = ref(false)
watch(statusAddCourses, (newStatus) => {
    formAddCourses.status = newStatus;
});

const statusAddCoursesFree = ref(false)
watch(statusAddCoursesFree, (newStatus) => {
    formAddCourses.isFree = newStatus;
});

const isOpenAddCourses = ref(false)
const closeAddCourses = () => {
    isOpenAddCourses.value = false;
}
const openAddCourses = () => {
    isOpenAddCourses.value = true;
}

const formAddCourses = useForm({
    title: null,
    description: null,
    preview: null,
    thumbnail: null,
    category_courses_id: null,
    selectedLevel: null,
    isFree: statusAddCoursesFree.value,
    price: null,
    status: statusAddCourses.value,
})

const handleAddCourses = () => {
    formAddCourses.post(route('courses.management.courses.add'), {
        onSuccess: () => {
            formAddCourses.reset()
            isOpenAddCourses.value = false;
        }
    })
}
/////////////////////////////////////////////////////////////////////////////////////////
const statusEditCourses = ref(false)
watch(statusEditCourses, (newStatus) => {
    formEditCourses.status = newStatus;
});
const statusEditCoursesFree = ref(false)
watch(statusEditCoursesFree, (newStatus) => {
    formEditCourses.isFree = newStatus;
});
const isOpenEditCourses = ref(false)
const formEditCourses = useForm({
    id:null,
    title: null,
    description: null,
    preview: null,
    thumbnail: null,
    category_courses_id: null,
    selectedLevel: null,
    isFree: statusEditCoursesFree.value,
    price: null,
    status: statusEditCourses.value,
})
const closeEditCourses = () => {
    isOpenEditCourses.value = false;
    formEditCourses.reset();
}
const openEditCourses = id => {
    isOpenEditCourses.value = true;
    const current = props.courses.data.find(item=> item.id === parseInt(id));
    formEditCourses.id = current.id;
    formEditCourses.title = current.title;
    formEditCourses.description = current.description;
    formEditCourses.thumbnail = current.thumbnail;
    formEditCourses.category_courses_id = props.category_courses.find(item=> item.id === current.category_courses_id);
    formEditCourses.selectedLevel = levels.find(level=>level.name === current.level);
    formEditCourses.price = current.price;
    statusEditCourses.value = current.status === 1;
    statusEditCoursesFree.value = current.is_free === 1;
}
const handleEditCourses = () => {
    formEditCourses.post(route('courses.management.courses.update',{courses:formEditCourses.id}), {
        onSuccess: () => {
            formEditCourses.reset()
            isOpenEditCourses.value = false;
        }
    })
}
const chooseFileEdit = e => {
    if (formEditCourses.preview) {
        URL.revokeObjectURL(formEditCourses.preview);
    }
    const file = e.target.files[0];
    formEditCourses.preview = file ? URL.createObjectURL(file) : null;
    formEditCourses.thumbnail = file ?? null; // Use thumbnail instead of image
}

const url = computed(() => {
    if (formEditCourses.preview) {
        return formEditCourses.preview;
    } else if (formEditCourses.thumbnail && typeof formEditCourses.thumbnail === 'string') {
        return `/storage/${formEditCourses.thumbnail}`;
    }
    return null;
})

</script>
<template>
    <Modal :is-open="isOpenAddCourses" @close="closeAddCourses">
        <Container @click.stop class="w-[600px] h-[650px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Create courses</h1>
            <div class="grid space-y-4 overflow-x-hidden">
                <div>
                    <InputField
                        placeholder="Enter courses title"
                        label="Courses title"
                        type="text"
                        v-model="formAddCourses.title"
                        :error="formAddCourses.errors.title"
                    />
                </div>
                <div>
                    <label class="text-sm block mb-1 cursor-pointer font-normal" for="descriptionAddCategory">Description</label>
                    <Tiptap :error="formAddCourses.errors.description" v-model="formAddCourses.description"
                            class="!w-[540px]"></Tiptap>
                </div>
                <div>
                    <h1 class="text-sm mb-1">Thumbnail URL</h1>
                    <label
                        class="border-[2px] overflow-hidden cursor-pointer hover:border-[#8278F2] transition border-gray-300 border-dashed rounded-md h-60 flex items-center justify-center"
                        for="thumbnail">
                        <div v-if="!formAddCourses.preview" class="flex flex-col items-center">
                            <i class="fa-solid fa-upload text-2xl"></i>
                            <h1 class="text-sm font-medium">Click to choose image</h1>
                            <p class="text-xs">JPG, PNG or GIF up to 5MB</p>
                        </div>
                        <div v-else>
                            <img class="size-full object-cover " :src="formAddCourses.preview" alt="">
                        </div>
                    </label>
                    <input id="thumbnail" @input="chooseFile" hidden type="file">
                    <div v-auto-animate>
                        <div v-if="formAddCourses.errors.thumbnail" class="text-red-400 mt-1 text-sm">
                            {{ formAddCourses.errors.thumbnail }}
                        </div>
                    </div>
                </div>
                <Listbox v-model="formAddCourses.category_courses_id" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 text-sm">Select category</h1>
                        <ListboxButton
                            class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full text-sm">
                            {{ formAddCourses.category_courses_id?.name || 'Select category' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind z-10 rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="category in category_courses"
                                    :key="category.id"
                                    :value="category"
                                >
                                    {{ category.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
                <div v-auto-animate>
                    <div class="text-red-400 mt-1 text-sm" v-if="!!formAddCourses.errors.category_courses_id">
                        {{ formAddCourses.errors.category_courses_id }}
                    </div>
                </div>
                <Listbox v-model="formAddCourses.selectedLevel" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 text-sm">Select level</h1>
                        <ListboxButton
                            class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full text-sm">
                            {{ formAddCourses.selectedLevel?.name || 'Select level' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind z-10 rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="level in levels"
                                    :key="level.id"
                                    :value="level"
                                >
                                    {{ level.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
                <div v-auto-animate>
                    <div class="text-red-400 mt-1 text-sm" v-if="!!formAddCourses.errors.selectedLevel">
                        {{ formAddCourses.errors.selectedLevel }}
                    </div>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Free Course</h1>
                        <p class="text-sm">This course will be {{ statusAddCoursesFree ? 'free' : 'paid' }} for
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusAddCoursesFree"
                                :class="statusAddCoursesFree ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusAddCoursesFree ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div v-auto-animate>
                    <div v-if="!statusAddCoursesFree" class="mb-4">
                        <InputField
                            placeholder="500.000"
                            label="Price"
                            type="number"
                            v-model="formAddCourses.price"
                            :error="formAddCourses.errors.price"
                        />
                    </div>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This courses will be {{ statusAddCourses ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusAddCourses"
                                :class="statusAddCourses ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusAddCourses ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="isOpenAddCourses = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button @click="handleAddCourses" :disabled="formAddCourses.processing" class="px-3 text-sm">Create
                        Courses
                    </Button>
                </div>
            </div>
        </Container>
    </Modal>
    <Modal :is-open="isOpenEditCourses" @close="closeEditCourses">
        <Container @click.stop class="w-[600px] h-[650px] main overflow-auto">
            <h1 class="text-xl font-medium mb-2">Edit courses</h1>
            <div class="grid space-y-4 overflow-x-hidden">
                <div>
                    <InputField
                        placeholder="Enter courses title"
                        label="Courses title"
                        type="text"
                        v-model="formEditCourses.title"
                        :error="formEditCourses.errors.title"
                    />
                </div>
                <div>
                    <label class="text-sm block mb-1 cursor-pointer font-normal" for="descriptionAddCategory">Description</label>
                    <Tiptap :error="formEditCourses.errors.description" v-model="formEditCourses.description" :content="formEditCourses.description"
                            class="!w-[540px]"></Tiptap>
                </div>
                <div>
                    <h1 class="text-sm mb-1">Thumbnail URL</h1>
                    <label
                        class="border-[2px] overflow-hidden cursor-pointer hover:border-[#8278F2] transition border-gray-300 border-dashed rounded-md h-60 flex items-center justify-center"
                        for="thumbnail">
                        <div v-if="!url" class="flex flex-col items-center">
                            <i class="fa-solid fa-upload text-2xl"></i>
                            <h1 class="text-sm font-medium">Click to choose image</h1>
                            <p class="text-xs">JPG, PNG or GIF up to 5MB</p>
                        </div>
                        <div v-else>
                            <img class="size-full object-cover" :src="url" alt="">
                        </div>
                    </label>
                    <input id="thumbnail" @change="chooseFileEdit" hidden type="file">
                    <div v-auto-animate>
                        <div v-if="formEditCourses.errors.thumbnail" class="text-red-400 mt-1 text-sm">
                            {{ formEditCourses.errors.thumbnail }}
                        </div>
                    </div>
                </div>
                <Listbox v-model="formEditCourses.category_courses_id" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 text-sm">Select category</h1>
                        <ListboxButton
                            class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full text-sm">
                            {{ formEditCourses.category_courses_id?.name || 'Select category' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind z-10 rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="category in category_courses"
                                    :key="category.id"
                                    :value="category"
                                >
                                    {{ category.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
                <div v-auto-animate>
                    <div class="text-red-400 mt-1 text-sm" v-if="!!formEditCourses.errors.category_courses_id">
                        {{ formEditCourses.errors.category_courses_id }}
                    </div>
                </div>
                <Listbox v-model="formEditCourses.selectedLevel" class="mb-6">
                    <div class="relative">
                        <h1 class="mb-2 ml-1 text-sm">Select level</h1>
                        <ListboxButton
                            class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left w-full text-sm">
                            {{ formEditCourses.selectedLevel?.name || 'Select level' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind z-10 rounded-md p-2 box-shadow-copy w-full">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="level in levels"
                                    :key="level.id"
                                    :value="level"
                                >
                                    {{ level.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
                <div v-auto-animate>
                    <div class="text-red-400 mt-1 text-sm" v-if="!!formEditCourses.errors.selectedLevel">
                        {{ formEditCourses.errors.selectedLevel }}
                    </div>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Free Course</h1>
                        <p class="text-sm">This course will be {{ statusEditCoursesFree ? 'free' : 'paid' }} for
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusEditCoursesFree"
                                :class="statusEditCoursesFree ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusEditCoursesFree ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div v-auto-animate>
                    <div v-if="!statusEditCoursesFree" class="mb-4">
                        <InputField
                            placeholder="500.000"
                            label="Price"
                            type="number"
                            v-model="formEditCourses.price"
                            :error="formEditCourses.errors.price"
                        />
                    </div>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This courses will be {{ statusEditCourses ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusEditCourses"
                                :class="statusEditCourses ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusEditCourses ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="isOpenEditCourses = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button @click="handleEditCourses" :disabled="formEditCourses.processing" class="px-3 text-sm">Update
                        Courses
                    </Button>
                </div>
            </div>
        </Container>
    </Modal>
    <Container class="flex justify-between items-center">
        <div class="flex items-center gap-x-4">
            <div>
                <h1 class="font-medium text-xl">Courses</h1>
                <p class="text-base">Manage your courses</p>
            </div>
            <div>
                <Listbox v-model="selectedCategory">
                    <div class="relative">
                        <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left min-w-40 text-sm">
                            {{ selectedCategory?.name || 'Select category' }}
                        </ListboxButton>
                        <transition name="list">
                            <ListboxOptions
                                class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full z-30">
                                <ListboxOption
                                    class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                    v-for="item in categoryCoursesParams"
                                    :key="item.id"
                                    :value="item">
                                    {{ item.name }}
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
        </div>
        <div>
            <Button @click="openAddCourses" class="!py-[5px] px-3"><span><i
                class="fa-solid fa-plus text-xs -translate-y-[2px] mr-2"></i></span> Add courses
            </Button>
        </div>
    </Container>
    <MessageSession class="mt-4" :message="message" :status="status"/>
    <div class="grid grid-cols-4 gap-x-5 gap-y-5 mt-6 ">
        <div class="rounded-md overflow-hidden box-shadow-copy" v-for="item in courses.data">
            <div class="relative">
                <img class="h-40 object-cover w-full" :src="`/storage/${item.thumbnail}`" alt="">
                <div class="text-xs absolute left-4 top-3 bg-gray-200 py-1 px-3 text-black font-medium rounded-full">{{ item.category_courses.name }}</div>
                <div class="text-xs absolute top-4 right-4">
                                            <span v-if="item.level.includes('Easy')"
                                                  class="bg-green-100 py-1 px-3 rounded-full text-green-500  font-medium">Easy</span>
                    <span v-if="item.level.includes('Medium')"
                          class="bg-yellow-100 py-1 px-3 rounded-full text-yellow-500  font-medium">Medium</span>
                    <span v-if="item.level.includes('Hard')"
                          class="bg-red-100 py-1 px-3 rounded-full text-red-500  font-medium">Hard</span>
                    <span v-if="item.level.includes('Extremely')"
                          class="bg-purple-100 py-1 px-3 rounded-full text-purple-500  font-medium">Extremely</span>
                </div>
            </div>
            <div class="p-6 bg-content dark:dark-bg-content ">
                <div class="flex items-center justify-between">
                    <div class="text-xs">
                        <span class=" px-3 py-1 font-medium  rounded-md bg-green-100 text-green-500"
                              v-if="item.status">Active</span>
                        <span class=" px-3 py-1 rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
                    </div>
                    <div class="text-sm">
                        <span
                            v-if="!item.is_free"
                            class="text-[#7367F0] font-medium">{{
                                formatCurrency(item.price)
                            }}</span>
                        <span v-else class="text-[#7367F0] font-medium">Free</span>
                    </div>
                </div>
                <h1 class="mt-3 mb-1 font-medium text-sm line-clamp-2 h-12">{{ item.title }}</h1>
                <div class="flex items-center gap-x-2 text-xs">
                    <div
                        class="dark:dark-hover-selected font-medium px-3 py-2 rounded-md cursor-pointer border-[1px] dark:border-none border-gray-200 w-full flex items-center justify-center"><span>
                            <i class="fa-solid fa-book-open mr-1 text-sm"></i></span> Contents
                    </div>
                    <div
                        @click="openEditCourses(item.id)"
                        class=" dark:dark-hover-selected font-medium px-3 py-2 rounded-md border-[1px] dark:border-none border-gray-200 cursor-pointer w-full flex items-center justify-center"><span><i
                        class="fa-solid fa-pen-to-square mr-1 text-sm"></i></span> Edit
                    </div>
                </div>
            </div>
        </div>
    </div>
    <PaginationLinks class="mt-4" :paginator="courses"></PaginationLinks>
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
