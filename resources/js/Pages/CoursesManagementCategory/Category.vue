<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import Modal from "../../Components/Modal.vue";
import InputField from "../../Components/InputField.vue";
import {ref, watch} from "vue";
import {Switch} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import MessageSession from "../../Components/MessageSession.vue";
import {vAutoAnimate} from '@formkit/auto-animate';

const props = defineProps({
    category_courses: Object,
    message: String,
    status: Boolean,
})
const statusAddCategory = ref(false)
watch(statusAddCategory, (newStatus) => {
    formAddCategory.status = newStatus;
});
const isAddCategory = ref(false)
const closeAddCategory = () => {
    isAddCategory.value = false;
}
const openAddCategory = () => {
    isAddCategory.value = true;
}
const formAddCategory = useForm({
    name: null,
    description: null,
    status: statusAddCategory.value
})
const handleAddCategory = () => {
    formAddCategory.post(route('courses.management.category.add'), {
        onSuccess: () => {
            isAddCategory.value = false;
            statusAddCategory.value = false
            formAddCategory.reset('name', 'description');
        }
    })
}
const statusEditCategory = ref(false)
watch(statusEditCategory, (newStatus) => {
    formEditCategory.status = newStatus;
});
const formEditCategory = useForm({
    id:null,
    name: null,
    description: null,
    status: statusAddCategory.value
})
let isEditCategory = ref(false)
const closeEditCategory = () => {
    isEditCategory.value = false;
    formEditCategory.reset();
}
const openEditCategory = id => {
    isEditCategory.value = true;
    const category = props.category_courses.find(item=> item.id === parseInt(id));
    formEditCategory.id = category.id
    formEditCategory.name = category.name;
    formEditCategory.description = category.description;
    statusEditCategory.value = category.status;
}

const handleUpdateCategory = () => {
    formEditCategory.post(route('courses.management.category.update',{category_courses:formEditCategory.id}), {
        onSuccess: () => {
            isEditCategory.value = false;
            formEditCategory.reset('name', 'description','id');
        }
    })
}
</script>
<template>
    <Modal :is-open="isAddCategory" @close="closeAddCategory">
        <Container @click.stop class="w-[500px]">
            <h1 class="text-xl font-medium mb-2">Create category</h1>
            <form class="space-y-6" @submit.prevent="handleAddCategory">
                <div>
                    <InputField
                        placeholder="Enter category name"
                        label="Category name"
                        type="text"
                        v-model="formAddCategory.name"
                        :error="formAddCategory.errors.name"
                    />
                    <p class="text-sm mt-2">This is the name will be displayed for this category</p>
                </div>
                <div>
                    <label class="text-sm block mb-1 cursor-pointer font-normal" for="descriptionAddCategory">Description</label>
                    <textarea v-model="formAddCategory.description" placeholder="Enter category description" rows="5"
                              id="descriptionAddCategory"
                              class="resize-none disabled:opacity-70 w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"></textarea>
                    <div v-auto-animate>
                        <p v-if="formAddCategory.errors.description" class="text-red-400 mt-1 text-sm">
                            {{ formAddCategory.errors.description }}</p>
                    </div>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This category will be {{ statusAddCategory ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusAddCategory"
                                :class="statusAddCategory ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusAddCategory ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="isAddCategory = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button :disabled="formAddCategory.processing" class="px-3 text-sm">Create Category</Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Modal :is-open="isEditCategory" @close="closeEditCategory">
        <Container @click.stop class="w-[500px]">
            <h1 class="text-xl font-medium mb-2">Edit category</h1>
            <form class="space-y-6" @submit.prevent="handleUpdateCategory">
                <div>
                    <InputField
                        placeholder="Enter category name"
                        label="Category name"
                        type="text"
                        v-model="formEditCategory.name"
                        :error="formEditCategory.errors.name"
                    />
                    <p class="text-sm mt-2">This is the name will be displayed for this category</p>
                </div>
                <div>
                    <label class="text-sm block mb-1 cursor-pointer font-normal" for="descriptionAddCategory">Description</label>
                    <textarea v-model="formEditCategory.description" placeholder="Enter category description" rows="5"
                              id="descriptionAddCategory"
                              class="resize-none disabled:opacity-70 w-full py-[7px] px-[14px] border-gray-300 border-[1px] rounded-md transition-all focus:outline-none focus:ring-1 focus:ring-[#7367F0] bg-transparent"></textarea>
                    <div v-auto-animate>
                        <p v-if="formEditCategory.errors.description" class="text-red-400 mt-1 text-sm">
                            {{ formEditCategory.errors.description }}</p>
                    </div>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This category will be {{ statusEditCategory ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusEditCategory"
                                :class="statusEditCategory ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusEditCategory ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="isEditCategory = false"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button :disabled="formEditCategory.processing" class="px-3 text-sm">Update Category</Button>
                </div>
            </form>
        </Container>
    </Modal>

    <Container class="flex justify-between items-center">
        <div>
            <h1 class="font-medium text-xl">Courses categories</h1>
            <p class="text-base">Manage your courses categories</p>
        </div>
        <div>
            <Button @click="openAddCategory" class="!py-[5px] px-3"><span><i
                class="fa-solid fa-plus text-xs -translate-y-[2px] mr-2"></i></span> Add category
            </Button>
        </div>
    </Container>
    <MessageSession
        class="mt-6"
        :message="message"
        :status="status"
    />
    <div class="grid grid-cols-3 mt-6 gap-x-6">
        <Container v-for="category in category_courses">
            <div class="flex items-center justify-between">
                <h1 class="text-lg font-medium">{{ category.name }}</h1>
                <span class="pt-[3px] text-sm font-medium pb-[5px] px-3 rounded-md bg-green-100 text-green-500"
                      v-if="category.status">Active</span>
                <span class="pt-[3px] pb-[5px] px-3 rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
            </div>
            <p class="h-10 line-clamp-2 text-sm mt-1 text-gray-500 dark:dark-text-primary">
                {{ category.description }}</p>
            <div class="flex justify-end items-center gap-x-4 mt-3">
                <div @click="openEditCategory(category.id)" class="hover-selected dark:dark-hover-selected font-medium px-3 py-1 rounded-md text-sm cursor-pointer"><span><i
                    class="fa-solid fa-pen-to-square text-sm mr-1"></i></span> Edit
                </div>
            </div>
        </Container>
    </div>
</template>
