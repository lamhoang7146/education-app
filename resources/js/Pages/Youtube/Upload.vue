<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Upload Video</h1>
        <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Video Title</label>
                <input
                    type="text"
                    id="title"
                    v-model="form.title"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter video title"
                />
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Video Description</label>
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter video description"
                ></textarea>
            </div>
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Video File</label>
                <input
                    type="file"
                    id="file"
                    @change="handleFileChange"
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                />
            </div>
            <button
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Upload
            </button>
        </form>
    </div>
</template>

<script>
import { ref } from "vue";

export default {
    name: "UploadVideo",
    setup() {
        const form = ref({
            title: "",
            description: "",
            file: null,
        });

        const handleFileChange = (event) => {
            form.value.file = event.target.files[0];
        };

        const handleSubmit = async () => {
            const formData = new FormData();
            formData.append("title", form.value.title);
            formData.append("description", form.value.description);
            formData.append("file", form.value.file);

            try {
                const response = await fetch("/youtube/upload", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                });

                if (!response.ok) {
                    throw new Error("Failed to upload video.");
                }

                const data = await response.json();
                alert(`Video uploaded successfully! Video ID: ${data.videoId}`);
            } catch (error) {
                console.error("Error uploading video:", error);
                alert("Error uploading video. Please try again.");
            }
        };

        return {
            form,
            handleFileChange,
            handleSubmit,
        };
    },
};
</script>

<style scoped>
/* Add any additional styles here */
</style>
