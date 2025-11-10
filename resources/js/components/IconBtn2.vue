<template>
    <button
        class="w-12 h-12 flex justify-center items-center rounded-[48px] overflow-hidden relative shadow-[0px_4px_4px_0px_rgba(0,0,0,0.16)] shadow-[inset_0px_4px_4px_0px_rgba(241,66,66,0.08)] bg-white drop-shadow-lg"
    >
        <slot name="icon"></slot>

        <div
            v-if="count"
            class="w-2.5 h-2.5 right-2 top-2 absolute bg-error rounded-full text-center flex justify-center items-center text-white text-[7px] font-bold font-['Roboto']"
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
