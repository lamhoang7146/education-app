<script setup>
defineProps({
    paginator: {
        type: Object,
        required: true,
    },
});

const makeLabel = (label) => {
    if (label.includes("Previous")) {
        return "<<";
    } else if (label.includes("Next")) {
        return ">>";
    } else {
        return label;
    }
};
</script>

<template>
    <div class="flex justify-between items-center">
        <div class="flex items-center rounded-md overflow-hidden shadow-lg">
            <component v-for="link in paginator.links" :key="link.url"
                       :is="link.url ? 'Link' : 'span'"
                       as="button"
                       type="button"
                       :href="link.url"
                       preserve-scroll

                       v-html="makeLabel(link.label)"
                       class="border-x border-slate-50 w-12 h-12 grid place-items-center bg-white transition"
                       :class="{
            ' hover:bg-slate-300': link.url,
            'text-zinc-400': !link.url,
            'font-bold text-blue-500': link.active,
          }"
            />
        </div>


        <p class="text-slate-600 text-sm">
            Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} results
        </p>
    </div>
</template>
