<template>
    <button
        class="w-10 h-10 flex justify-center items-center bg-primary-50 rounded-[48px] relative"
    >
        <slot name="icon"></slot>

        <div
            v-if="count"
            class="w-2.5 h-2.5 right-2 top-1 absolute bg-error rounded-full text-center flex justify-center items-center text-white text-[7px] font-bold font-['Roboto']"
        >
            {{ count }}
        </div>
    </button>
</template>

<script setup>
const props = defineProps({
    colorClass: {
        type: String,
        default: "fill-primary",
    },
    count: {
        type: [String, Number],
        default: 0,
    },
});
</script>
