<script setup>
import io from 'socket.io-client'
import {onMounted,onUnmounted} from "vue";
import Container from "../Components/Container.vue";
import {ref} from 'vue'
const socket = io("http://localhost:8765");

onMounted(()=>{
    socket.on('connect',()=>{
        console.log('Connect successfully!')
    })
})

const dataChunk = ref("")

const inputValue = ref(null);
onUnmounted(()=>{
    socket.off("disconnect");
})

const handleSubmit = ()=>{
    socket.emit("message",inputValue.value)
    inputValue.value = "";
}

socket.on('response_chunk',(data)=>{
    dataChunk.value += data;
    console.log(dataChunk.value)
})

</script>
<template>
    <div class="h-[86vh] w-full">
        <div class="flex items-center justify-center h-[60%]">
            <div class="flex flex-col items-center gap-y-4 w-[50%]">
                <h1 class="text-3xl font-medium">What can I help with?</h1>
                <Container class="w-full rounded-3xl">
                    <form @submit.prevent="handleSubmit">
                        <input v-model="inputValue" autocomplete="new-password" class="disabled:opacity-70 w-full py-[7px] px-[14px] rounded-md transition-all outline-none bg-transparent" placeholder="Ask anything about this system then press enter">
                    </form>
                </Container>
            </div>
        </div>
<!--        <div></div>-->
    </div>
</template>
