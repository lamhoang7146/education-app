<script setup>
import Container from "../../Components/Container.vue";
import {useForm} from "@inertiajs/vue3";
import InputField from "../../Components/InputField.vue";
import Button from "../../Components/Button.vue";
import {ref, watchEffect} from 'vue';
const props = defineProps({
    permissions: Object,
    getRolePermission: Object,
    getRoleDetail:Object,
})
const permissions = ref(
    props.permissions
);

const getRolePermission = ref(
    props.getRolePermission
);
const form = useForm({
    name: props.getRoleDetail.name,
    permission_id: [],
    is_important: props.getRoleDetail.is_important === 1,
});

const rolePermissions = getRolePermission.value
    .map(rp => rp.permission_id);


watchEffect(() => {
    const selectedPermissions = [];
    permissions.value.forEach(permission => {
        permission.groups.forEach(group => {
            if (rolePermissions.includes(group.id)) {
                selectedPermissions.push(group.id);
            }
        });
    });
    form.permission_id = selectedPermissions;
});


const submit = () => {
    const id = route().params.id;
    form.post(route('role.update', {id: id}));
}
</script>
<template>
    <Container>
        <h1 class="text-center font-medium text-xl">Edit role</h1>
        <p class="text-center mt-2 opacity-90">Set role permission</p>
        <form @submit.prevent="submit">
            <InputField
                label="Role name"
                placeholder="Enter Role Name"
                type="text"
                v-model="form.name"
                :error="form.errors.name"
            />
            <h1 class="mt-4 mb-2 font-medium text-xl">Role permissions</h1>
            <div class="grid grid-cols-[100px_1fr] border-b-[1px] border-gray-300" v-for="permission in permissions"
                 :key="permission.id">
                <div class="py-2 font-medium">{{ permission.name }}</div>
                <div class="flex items-center justify-end">
                    <div class="py-3 px-2 w-[180px]" v-for="group in permission.groups" :key="group.id">
                        <label :for="group.id" class="cursor-pointer">
                            <input
                                v-model="form.permission_id"
                                :value="group.id"
                                class="scale-125 translate-y-[2px] mr-2 cursor-pointer accent-[#8278F2]"
                                type="checkbox" :id="group.id">{{ group.name }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex items-center my-4">
                <input
                    class="scale-125 translate-y-[2px] mr-2 cursor-pointer accent-[#8278F2]"
                    v-model="form.is_important"
                    type="checkbox" id="auth"><label for="auth" class="cursor-pointer font-medium">Activation two factor
                authentication to protect the role</label>
            </div>



            <div class="flex justify-center">
                <Button :disabled="form.processing" class="px-4 !py-[6px]">Submit</Button>
            </div>
        </form>
    </Container>
</template>
