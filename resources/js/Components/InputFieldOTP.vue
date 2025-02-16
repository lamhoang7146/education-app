<script setup>
const props = defineProps({
    length: {
        type: Number,
        default: 6
    }
})
import {onMounted, ref} from "vue";
const otpEmit = defineEmits(["entered"]);
const container = ref();
const otpArray = ref([])
const handleEnter = (e, index) => {
    const children = container.value.children;
    const pressed = e.key;
    if (index > 0 && (pressed === 'Backspace' || pressed === 'Delete')) {
        otpArray.value[index] = null;
        setTimeout(() => {
            children[index - 1].focus();
        }, 100)
    } else {
        const matched = pressed.match(/^[0-9]$/);
        if (!matched) {
            otpArray.value[index] = null;
        } else if (index < props.length - 1) {
            setTimeout(() => {
                children[index + 1].focus();
            }, 100)
        }
        checkOTP();
    }
}
const checkOTP = () => {
    const children = container.value.children;
    let flag = true;
    for (let i = 0; i < props.length ; i++) {
        if (otpArray.value[i] == null) {
            children[i].classList.add('border-red-500');
            flag = false;
        }else{
            children[i].classList.remove('border-red-500')
        }
    }
    if(flag) otpEmit('entered',otpArray.value.join(''));
}
onMounted(()=>{
    const children = container.value.children;
    children[0].focus();
    for(let i = 0;i<props.length; i++) {
        otpArray.value[i] = null;
    }
})
</script>
<template>
    <div ref="container" class="space-x-3 mb-6 text-center">
        <input
            v-for="index in length"
            @keyup="(e)=> handleEnter(e, index - 1)"
            :key="index"
            v-model="otpArray[index - 1]"
            autocomplete="new-password"
            type="tel"
            maxlength="1"
            class="w-[50px] h-[50px] border-gray-300 border-[1px] rounded-md outline-0 text-center focus:border-[#7367F0] transition-all duration-200 bg-transparent disabled:dark-bg-behind">
    </div>
</template>
