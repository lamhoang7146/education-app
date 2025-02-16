<script setup>
import Container from "../../Components/Container.vue";
import MessageSession from "../../Components/MessageSession.vue";

defineProps({
    message: String,
    status: Boolean,
    roles: Object,
})
</script>
<template>
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
                <Link class="text-[#7367F0]" :href="route('role.edit',role.id)">Edit Role</Link>
            </div>
        </Container>
        <Container class="grid grid-cols-2 relative">
            <div class="place-items-center ">
                <img class="h-[115px] absolute left-[20%] top-[14px]"
                     src="../../../../public/storage/default/default_0.png" alt="">
            </div>
            <div class="">
                <div class="text-right">
                    <Link :href="route('role.show')"
                          class="px-3 w-[70%] bg-[#7367F0] text-white py-[6px] rounded-md text-sm outline-0 font-medium disabled:opacity-70 disabled:cursor-wait transition">
                        Add New Role
                    </Link>
                </div>
                <div class="mt-4 leading-5 text-right opacity-80">
                    <p>Add new role,</p>
                    <p>if it doesn't exist.</p>
                </div>
            </div>
        </Container>
    </div>

</template>
