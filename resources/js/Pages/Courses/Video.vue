<script setup>

import Container from "../../Components/Container.vue";
const props = defineProps({
    data: Object
});
console.log(props.data)
</script>
<template>
    <div class="rounded-md overflow-hidden">
        <iframe
            :src="`https://drive.google.com/file/d/${data?.google_drive_id}/preview`"
            allow="autoplay"
            class="w-full h-[550px]"
            allowfullscreen
        ></iframe>
    </div>
    <Container class="mt-4">
        <h1>{{data?.name}}</h1>
        <div class="border-b-[1px] bg-gray-100 my-4"></div>
        <div class="tiptap" v-html="data.description"></div>
    </Container>
</template>

<style>

</style>
