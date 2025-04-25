<script setup>
import {onMounted, onUnmounted, ref} from "vue";
import Container from "../Components/Container.vue";
import io from 'socket.io-client'
const socket = io(import.meta.env.VITE_AI_ANALYTICS_SERVER);
const dataChunk = ref("");
const inputValue = ref("");
const isLoading = ref(false);
const chatHistory = ref([]);

onMounted(() => {
    socket.on('connect', () => {
        console.log('Connect successfully!');
    });

    socket.on('response_chunk', (data) => {
        dataChunk.value += data;
    });

    socket.on('response_complete', (fullResponse) => {
        chatHistory.value.push({
            role: 'assistant',
            content: fullResponse
        });
        dataChunk.value = "";
        isLoading.value = false;
        scrollToBottom();
    });

    socket.on('error', (error) => {
        chatHistory.value.push({
            role: 'error',
            content: `Lỗi: ${error}`
        });
        isLoading.value = false;
        scrollToBottom();
    });
});

onUnmounted(() => {
    socket.off("connect");
    socket.off("response_chunk");
    socket.off("response_complete");
    socket.off("error");
    socket.off("disconnect");
});

const handleSubmit = () => {
    if (!inputValue.value.trim() || isLoading.value) return;

    chatHistory.value.push({
        role: 'user',
        content: inputValue.value
    });

    socket.emit("message", inputValue.value);
    isLoading.value = true;
    inputValue.value = "";
    scrollToBottom();
};

const scrollToBottom = () => {
    setTimeout(() => {
        const chatContainer = document.getElementById('chat-container');
        if (chatContainer) {
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: "smooth"
            });
        }
    }, 150);
};
</script>

<template>
    <div class="h-[86vh] w-full flex flex-col">
        <!-- Chat history -->
        <div id="chat-container" class="flex-1 overflow-y-auto p-4 main">
            <div v-if="chatHistory.length === 0" class="flex items-center justify-center h-full">
                <div class="text-center text-gray-500">
                    <p class="text-xl">Ask me about your data</p>
                    <p class="text-sm mt-2">I can help you query database and analyze information</p>
                </div>
            </div>

            <div v-else class="space-y-4">
                <div v-for="(message, index) in chatHistory" :key="index"

                     :class="{
                         'bg-content dark:dark-bg-content rounded-lg p-4 ml-auto max-w-[80%]': message.role === 'user',
                         'bg-red-100 rounded-lg p-3 max-w-[80%]': message.role === 'error',
                         'bg-content dark:dark-bg-content rounded-lg p-4 max-w-[80%]': message.role === 'assistant'
                     }">
                    <div v-if="message.role === 'user'" class="font-medium ">
                        {{ message.content }}
                    </div>

                    <div v-else-if="message.role === 'error'" class="text-red-600">
                        {{ message.content }}
                    </div>

                    <div v-else class="assistant-message whitespace-pre-wrap ">{{ message.content }}</div>
                </div>

                <!-- Loading response -->
                <div v-if="isLoading" class="bg-gray-200 rounded-lg p-3">
                    <div class="assistant-message whitespace-pre-wrap">{{ dataChunk }}</div>
                    <div class="flex items-center mt-2">
                        <div class="h-2 w-2 bg-gray-400 rounded-full mr-1 animate-bounce"></div>
                        <div class="h-2 w-2 bg-gray-400 rounded-full mr-1 animate-bounce"
                             style="animation-delay: 0.2s"></div>
                        <div class="h-2 w-2 bg-gray-400 rounded-full animate-bounce"
                             style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input section -->
        <div class="border-t mb-2">
            <Container class="w-full rounded-3xl !p-2 !pr-6">
                <form @submit.prevent="handleSubmit" class="relative flex items-center justify-between">
                    <input
                        v-model="inputValue"
                        autocomplete="off"
                        class="disabled:opacity-70 w-full py-[12px] px-[14px] rounded-md transition-all outline-none bg-transparent pr-12"
                        placeholder="Your question here..."
                        :disabled="isLoading"
                    >
                    <button
                        type="submit"
                        class=""
                        :disabled="isLoading"
                    >
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </Container>
        </div>
    </div>
</template>

<style scoped>
.assistant-message {
    line-height: 1.6;
    word-wrap: break-word;
    white-space: pre-wrap;
    overflow-wrap: break-word;
    max-width: 100%;
    font-family: inherit;
}
</style>
