<script setup>
import Container from "../../Components/Container.vue";
import Button from "../../Components/Button.vue";
import Modal from "../../Components/Modal.vue";
import {ref, watch} from "vue";
import InputField from "../../Components/InputField.vue";
import {Switch} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import MessageSession from "../../Components/MessageSession.vue";
const props = defineProps({
    courses_id:String,
    courses_contents:Object,
    message:String,
    status:Boolean
})
const statusAddCoursesContent = ref(false)
watch(statusAddCoursesContent, (value) => {
    formAddCoursesContent.status = value
})
const isAddCoursesContent = ref(false)
const closeAddCoursesContent = () => {
    isAddCoursesContent.value = false
}
const openAddCoursesContent = () => {
    isAddCoursesContent.value = true
}
const formAddCoursesContent = useForm({
    name:null,
    status:statusAddCoursesContent.value
})
const handleAddCoursesContent = () => {
    formAddCoursesContent.post(route('courses.management.courses.content.add',{id:props.courses_id}), {
        preserveScroll: true,
        onSuccess: () => {
            formAddCoursesContent.reset()
            closeAddCoursesContent()
        }
    })
}
//
const statusEditCoursesContent = ref(false)
watch(statusEditCoursesContent, (value) => {
    formEditCoursesContent.status = value
})
const isEditCoursesContent = ref(false)
const closeEditCoursesContent = () => {
    isEditCoursesContent.value = false
    formEditCoursesContent.reset()
}
const openEditCoursesContent = id => {
    const data = props.courses_contents.find(item => item.id === id)
    formEditCoursesContent.id = parseInt(data.id)
    formEditCoursesContent.name = data.name
    statusEditCoursesContent.value = Boolean(data.status)
    isEditCoursesContent.value = true
}
const formEditCoursesContent = useForm({
    id:null,
    name:null,
    status:statusEditCoursesContent.value
})
const handleEditCoursesContent = () => {
    formEditCoursesContent.post(route('courses.management.courses.content.update',{id:props.courses_id}), {
        preserveScroll: true,
        onSuccess: () => {
            formEditCoursesContent.reset()
            closeEditCoursesContent()
        }
    })
}
</script>
<template>
    <Modal :is-open="isAddCoursesContent" @close="closeAddCoursesContent">
        <Container @click.stop class="w-[500px]">
            <h1 class="text-xl font-medium mb-2">Create courses content</h1>
            <form class="space-y-6" @submit.prevent="handleAddCoursesContent">
                <div>
                    <InputField
                        placeholder="Enter courses content name"
                        label="Courses content name"
                        type="text"
                        v-model="formAddCoursesContent.name"
                        :error="formAddCoursesContent.errors.name"
                    />
                    <p class="text-sm mt-2">This is the name will be displayed for this category</p>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This courses content will be {{ statusAddCoursesContent ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusAddCoursesContent"
                                :class="statusAddCoursesContent ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusAddCoursesContent ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="closeAddCoursesContent"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button :disabled="formAddCoursesContent.processing" class="px-3 text-sm">Create Courses content</Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Modal :is-open="isEditCoursesContent" @close="closeEditCoursesContent">
        <Container @click.stop class="w-[500px]">
            <h1 class="text-xl font-medium mb-2">Edit courses content</h1>
            <form class="space-y-6" @submit.prevent="handleEditCoursesContent">
                <div>
                    <InputField
                        placeholder="Enter courses content name"
                        label="Edit content name"
                        type="text"
                        v-model="formEditCoursesContent.name"
                        :error="formEditCoursesContent.errors.name"
                    />
                    <p class="text-sm mt-2">This is the name will be displayed for this courses content</p>
                </div>
                <div class="px-4 py-2 border-[1px] border-slate-300 rounded-md flex justify-between items-center">
                    <div>
                        <h1 class="font-medium">Active Status</h1>
                        <p class="text-sm">This courses content will be {{ statusEditCoursesContent ? 'visible' : 'hidden' }} to
                            users.</p>
                    </div>
                    <div>
                        <div>
                            <Switch
                                v-model="statusEditCoursesContent"
                                :class="statusEditCoursesContent ? 'dark-selected hover:dark-selected dark:hover:dark-selected' : 'hover-selected dark:dark-hover-selected'"
                                class="relative inline-flex h-[25px] w-[50px] shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75"
                            >
                                <span class="sr-only">Use setting</span>
                                <span
                                    aria-hidden="true"
                                    :class="statusEditCoursesContent ? 'translate-x-6' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-[21px] w-[21px] transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out"
                                />
                            </Switch>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-x-4">
                    <span @click="closeEditCoursesContent"
                          class="border-[1px] border-gray-200 px-3 rounded-md flex items-center justify-center cursor-pointer">Cancel
                    </span>
                    <Button :disabled="formEditCoursesContent.processing" class="px-3 text-sm">Update Courses content</Button>
                </div>
            </form>
        </Container>
    </Modal>
    <Container>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-medium">Courses content</h1>
                <p class="text-sm">Manager your courses content</p>
            </div>
            <Button @click="openAddCoursesContent" class="px-4 flex items-center gap-x-2"><i class="fa-solid fa-plus text-sm"></i> Add courses content</Button>
        </div>
    </Container>
    <MessageSession class="my-4" :message="message" :status="status" />
    <div class="grid gap-y-4">
        <Container v-for="item in courses_contents" class="flex items-center justify-between">
            <div class="flex items-center gap-x-2">
            <p class="text"><span class="font-medium">Module: </span><span>{{ item.name }}</span></p>
                <span class="pt-[3px] text-xs font-medium pb-[5px] px-3 rounded-md bg-green-100 text-green-500"
                      v-if="item.status">Active</span>
                <span class="pt-[3px] pb-[5px] px-3 text-xs font-medium rounded-md bg-red-100 text-red-500" v-else>Suspended</span>
            </div>
            <div class="flex items-center gap-x-2">
                <Link :href="route('courses.management.courses.content.item',{courses_id:courses_id,id:item.id})" class=" py-1 px-2 hover-selected text-primary rounded-sm cursor-pointer dark:dark-hover-selected">Add item</Link>
            <span @click="openEditCoursesContent(item.id)" class="hover:underline cursor-pointer underline-offset-2">Edit courses content</span>
            </div>
        </Container>
    </div>
 </template>
