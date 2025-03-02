<script setup>
import {onMounted, ref} from "vue";
import Container from "../../Components/Container.vue";

const isFullscreen = ref(false);

const toggleFullscreen = () => {
    isFullscreen.value = !isFullscreen.value;
};
onMounted(()=>{
    document.addEventListener("keydown", e => {
        if (e.key === "Escape") {
            isFullscreen.value = false;
        }
    });
})

</script>
<template>
    <Container>
        <div :class="isFullscreen ? 'fixed inset-0 z-50 bg-black' : ''">
            <div class="relative" >
                <iframe
                    id="player"
                    class="w-full h-96 rounded-md"
                    :class="isFullscreen ? 'w-screen h-screen' : ''"
                    src="https://www.youtube.com/embed/lkzhGTwzDjc?playsinline=1&rel=0&fs=0&modestbranding=1&showinfo=0&controls=1&disablekb=1"
                    title="YouTube video player"
                    allow="autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
                <div class="absolute top-0 h-16 w-full">

                </div>
                <div class="absolute bottom-1 h-24 w-20 right-[0px]">
                    <div class="flex items-end h-full justify-end mr-4">
                        <div
                            v-if="isFullscreen"
                            @click="toggleFullscreen"
                            class="hover-selected dark:dark-hover-selected px-6 py-1 rounded-md cursor-pointer"
                        >
                            <span><i class="fa-solid fa-compress text-sm"></i></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="mt-2">
            <div class="text-right">
                <button
                    v-if="!isFullscreen"
                    @click="toggleFullscreen"
                    class="hover-selected dark:dark-hover-selected px-4 py-2 rounded-md "
                >
                    <span><i class="fa-solid fa-expand text-sm"></i></span>
                    Fullscreen
                </button>
            </div>
        </div>


    </Container>
</template>

<style>
.fixed {
    overflow: hidden;
}
</style>
