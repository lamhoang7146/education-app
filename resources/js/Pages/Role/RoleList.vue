<script setup>
import Container from "../../Components/Container.vue";
import MessageSession from "../../Components/MessageSession.vue";
import {usePage} from '@inertiajs/vue3';
const permissionsOfUser = usePage().props.auth.user?.permissions;
const props = defineProps({
    message: String,
    status: Boolean,
    roles: Object,
    permissions: Object,
    rolePermissions: Object
})
import Modal from "../../Components/Modal.vue";
import {ref} from "vue";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import {useForm} from "@inertiajs/vue3";
import {route} from "ziggy-js";

let isModalOpen = ref(false);
const closeModal = () => {
    isModalOpen.value = false;
}
const openModal = () => {
    isModalOpen.value = true;
}
const form = useForm({
    name: null,
    permission_id: [],
    is_important: false
})
const submit = () => {
    form.post(route('role.add'), {
        onSuccess: () => {
            isModalOpen.value = false;
            form.reset();
        }
    })
}
////////////////////////////////////////////
let isModalOpenEdit = ref(false);

const formEdit = useForm({
    id:null,
    name: null,
    permission_id: null,
    is_important: null,
});
const openModalEdit = id => {
    formEdit.id = id
    formEdit.permission_id = props.rolePermissions.filter(item => {
        if(item.role_id === id){
            return item.permission_id;
        }
    }).map(item=> item.permission_id);
    const getDetail = props.roles.find(item => item.id === id)
    formEdit.name = getDetail.name
    formEdit.is_important = getDetail.is_important === 1
    isModalOpenEdit.value = true;

}
const closeModalEdit = () => {
    isModalOpenEdit.value = false;
    formEdit.reset()

}
const update = () => {
    formEdit.post(route('role.update', {id: formEdit.id}),{
        onSuccess: () => {
            isModalOpenEdit.value = false;
            formEdit.reset();
        }
    });

}
</script>
<template>
    <Modal :isOpen="isModalOpen" @close="closeModal">
        <div @click.stop
             class="w-[1250px] h-[90%] py-16 main px-16 bg-content dark:dark-bg-content rounded-md box-shadow-copy overflow-auto">
            <h1 class="text-center font-medium text-2xl">Add new role</h1>
            <p class="text-center mt-2 opacity-90">Set role permission</p>
            <form @submit.prevent="submit">
                <InputField
                    label="Role name"
                    placeholder="Enter Role Name"
                    type="text"
                    v-model="form.name"
                    :error="form.errors.name"
                />
                <h1 class="mt-6 mb-4 font-medium text-xl">Role permissions</h1>
                <div class="grid grid-cols-[200px_repeat(4,220px)] items-start border-b border-gray-200 py-4 gap-y-3 px-4 font-medium transition">
                    <div>Role</div>
                    <div>Get</div>
                    <div>Add</div>
                    <div>Edit</div>
                    <div>Delete</div>
                </div>
                <div v-for="permission in permissions"
                     class="grid grid-cols-[200px_repeat(4,220px)] items-start border-b border-gray-200 py-4 gap-y-3 px-4 transition hover:hover-selected dark:hover:dark-hover-selected">

                    <div class="font-semibold">
                        {{ permission.name }}
                    </div>

                    <template v-for="group in permission.groups">
                        <div class="flex items-center space-x-2">
                            <input
                                v-model="form.permission_id"
                                :id="group.id"
                                :value="group.id"
                                type="checkbox"
                                class="accent-[#8278F2] w-4 h-4 cursor-pointer hover:hover-selected dark:dark-hover-selected"
                            />
                            <label :for="group.id" class=" text-sm cursor-pointer">
                                {{ group.name }}
                            </label>
                        </div>
                    </template>
                </div>

                <div class="flex items-center my-6">
                    <input
                        class="scale-125 translate-y-[2px] mr-2 cursor-pointer accent-[#8278F2]"
                        :checked="form.is_important"
                        v-model="form.is_important"
                        type="checkbox" id="auth"><label for="auth" class="cursor-pointer font-medium">Activation two
                    factor
                    authentication to protect the role</label>
                </div>
                <div class="flex justify-center">
                    <Button :disabled="form.processing" class="px-4 !py-[6px]">Submit</Button>
                </div>
            </form>
        </div>
    </Modal>
    <Modal :isOpen="isModalOpenEdit" @close="closeModalEdit">
        <div @click.stop
             class="w-[1250px] h-[90%] py-16 main px-16 bg-content dark:dark-bg-content rounded-md box-shadow-copy overflow-auto">
            <h1 class="text-center font-medium text-2xl">Edit new role</h1>
            <p class="text-center mt-2 opacity-90">Set role permission</p>
            <form @submit.prevent="update">
                <InputField
                    label="Role name"
                    placeholder="Enter Role Name"
                    type="text"
                    v-model="formEdit.name"
                    :error="formEdit.errors.name"
                    :disabled="!permissionsOfUser?.includes('Update role')"
                />
                <h1 class="mt-6 mb-4 font-medium text-xl">Role permissions</h1>
                <div class="grid grid-cols-[200px_repeat(4,220px)] items-start border-b border-gray-200 py-4 gap-y-3 px-4 font-medium transition">
                    <div>Role</div>
                    <div>Get</div>
                    <div>Add</div>
                    <div>Edit</div>
                    <div>Delete</div>
                </div>
                <div v-for="permission in permissions"
                     class="grid grid-cols-[200px_repeat(4,220px)] items-start border-b border-gray-200 py-4 gap-y-3 px-4 transition hover:hover-selected dark:hover:dark-hover-selected">

                    <div class="font-semibold">
                        {{ permission.name }}
                    </div>

                    <template v-for="group in permission.groups">
                        <div class="flex items-center space-x-2">
                            <input
                                v-model="formEdit.permission_id"
                                :id="group.id"
                                :value="group.id"
                                type="checkbox"
                                class="accent-[#8278F2] w-4 h-4 cursor-pointer hover:hover-selected dark:dark-hover-selected"
                            />
                            <label :for="group.id" class=" text-sm cursor-pointer">
                                {{ group.name }}
                            </label>
                        </div>
                    </template>
                </div>
                <div class="flex items-center my-6">
                    <input
                        class="scale-125 translate-y-[2px] mr-2 cursor-pointer accent-[#8278F2]"
                        :checked="formEdit.is_important"
                        v-model="formEdit.is_important"
                        type="checkbox" id="auth"><label for="auth" class="cursor-pointer font-medium">Activation two
                    factor
                    authentication to protect the role</label>
                </div>
                <div v-if="permissionsOfUser?.includes('Update role')" class="flex justify-center">
                    <Button :disabled="form.processing" class="px-4 !py-[6px]">Submit</Button>
                </div>
            </form>
        </div>
    </Modal>
    <h1 class="font-medium text-2xl">Roles List</h1>
    <p class="mt-2 mb-5 opacity-80">A role provided access to predefined menus and features so that depending on
        assigned role an administrator can have access to what he need</p>
    <MessageSession
        class="mb-5"
        :status="status"
        :message="message"
    />
    <div class="grid grid-cols-3 gap-6">
        <Container v-for="role in roles" :key="role.id">
            <div class="flex justify-between items-center">
                <h1>Maybe</h1>
                <i v-if="role.is_important" class="fa-solid fa-lock"></i>
            </div>
            <div class="mt-4">
                <h1 class="font-medium text-lg">{{ role.name }}</h1>
                <span v-if="permissionsOfUser?.includes('Edit role')" class="text-[#7367F0] cursor-pointer" @click="openModalEdit(role.id)">Edit Role</span>
            </div>
        </Container>
        <Container class="grid grid-cols-2 relative">
            <div class="place-items-center ">
                <img class="h-[115px] absolute left-[20%] top-[14px]"
                     src="../../../../public/storage/default/default_0.png" alt="">
            </div>
            <div class="">
                <div class="text-right">
                    <span
                        v-if="permissionsOfUser?.includes('Add role')"
                        @click="openModal"
                        class="px-3 w-[70%] bg-[#7367F0] text-white py-[6px] rounded-md text-sm outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition cursor-pointer">
                        Add New Role
                    </span>
                </div>
                <div class="mt-4 leading-5 text-right opacity-80">
                    <p>Add new role,</p>
                    <p>if it doesn't exist.</p>
                </div>
            </div>
        </Container>
    </div>

</template>
