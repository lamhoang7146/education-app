<script setup>
import LayoutAuth from "../../Layouts/LayoutStudy.vue";
import MessageSession from "../../Components/MessageSession.vue";
defineOptions({
    layout: LayoutAuth,
});
const props = defineProps({
    courses_detail: Object,
    content_item:Object,
    content_type:String,
    message:String,
    status:Boolean
})

import Container from "../../Components/Container.vue";
import CoursesContent from "../../Components/CoursesContent.vue";
import Video from "./Video.vue";
import Quiz from "./Quiz.vue";
</script>
<template>
    <MessageSession
    :message="message"
    :status="status"
    class="mb-4"
    />
    <div class="grid grid-cols-[3fr_1fr] gap-x-4 grid-rows-2">
        <div class="grid row-span-2">
            <component
                :data="content_item"
                v-if="content_type"
                :is="content_type === 'video' ? Video : Quiz" />
            <div v-else>
                <h1>You must select section you want</h1>
            </div>
        </div>
        <Container class="grid row-span-1">
            <div>
                <CoursesContent
                    v-for="content in courses_detail?.courses_contents"
                    :key="content.id"
                    :content="content"
                />
            </div>
        </Container>
    </div>
</template>
