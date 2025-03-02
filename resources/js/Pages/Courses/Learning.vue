<script setup>
import Container from "../../Components/Container.vue";
import CoursesContent from "../../Components/CoursesContent.vue";
import Video from "./Video.vue";
import Quiz from "./Quiz.vue";
import {ref} from "vue";
const data = ref({
    type:'video'
})
</script>
<template>
    <div class="flex items-start gap-x-4">
        <div class="w-2/3">
            <component v-if="data.type" :is="data.type === 'video' ? Video : Quiz"></component>
            <div v-else>
                <h1>You must select section you want</h1>
            </div>
        </div>
        <Container class="w-1/3">
            <div class="overflow-hidden rounded-md border-[1px] border-gray-200 dark:border-opacity-20">
                <CoursesContent></CoursesContent>
            </div>
        </Container>
    </div>
</template>
