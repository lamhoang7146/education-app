<script setup>
import {Editor, EditorContent} from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import Underline from "@tiptap/extension-underline";
import Heading from "@tiptap/extension-heading";
import BulletList from "@tiptap/extension-bullet-list";
import OrderedList from "@tiptap/extension-ordered-list";
import ListItem from "@tiptap/extension-list-item";
import Blockquote from "@tiptap/extension-blockquote";
import {vAutoAnimate} from '@formkit/auto-animate';
import {onBeforeUnmount} from "vue";
const props = defineProps({
    modelValue:String,
    error:String,
    content:String
})
const emit = defineEmits(['update:modelValue']);
const editor = new Editor({
    extensions: [
        StarterKit.configure({
            bulletList: false, // Tắt trong StarterKit
            blockquote: false, // Tắt trong StarterKit
            listItem: false,   // Tắt trong StarterKit
            orderedList: false, // Tắt trong StarterKit
        }),
        BulletList, // Thêm riêng
        OrderedList, // Thêm riêng
        Blockquote, // Thêm riêng
        ListItem, // Thêm riêng
        Underline,
        Heading.config
    ],
    onUpdate:({editor})=>{
        emit("update:modelValue", editor.getHTML());
    },
    content: props.content,
    editorProps: {
        attributes: {
            class: 'prose focus:outline-none p-2 min-h-[200px] overflow-x-hidden max-w-full w-full break-words whitespace-normal',
        },
    },
});
onBeforeUnmount(() => {
    editor.destroy();
});
const model = defineModel();

</script>
<template>
    <div class="w-full mx-auto bg-content dark:dark-bg-content rounded-lg shadow-md overflow-hidden border border-editor-border">
        <div class="bg-editor-toolbar p-2 border-b border-editor-border flex flex-wrap gap-2 wrapper-tiptap">
            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                :class="{ 'active': editor.isActive('bold') }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-bold"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                :class="{ 'active': editor.isActive('italic') }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-italic"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleUnderline().run()"
                :class="{ 'active': editor.isActive('underline') }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-underline"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                :class="{ 'active': editor.isActive('heading', { level: 1 }) }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-heading"></i><span class="ml-[2px] font-medium">1</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                :class="{ 'active': editor.isActive('heading', { level: 2 }) }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-heading text-sm"></i><span class="ml-[2px] font-medium">2</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                :class="{ 'active': editor.isActive('heading', { level: 3 }) }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-heading text-sm"></i><span class="ml-[2px] font-medium">3</span>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                :class="{ 'active': editor.isActive('bulletList') }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-list-ul"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                :class="{ 'active': editor.isActive('orderedList') }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-list-ol"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleBlockquote().run()"
                :class="{ 'active': editor.isActive('blockquote') }"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-quote-left"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().undo().run()"
                :disabled="!editor.can().undo()"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-rotate-left"></i>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().redo().run()"
                :disabled="!editor.can().redo()"
                class="editor-toolbar-button"
            >
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>
        <div class="bg-editor-content">
            <editor-content v-model="model" :editor="editor" class="min-h-[200px] tiptap" />
        </div>

    </div>
    <div v-auto-animate>
        <p v-if="!!error" class="text-red-400 mt-1 text-sm">{{error}}</p>
    </div>
</template>
