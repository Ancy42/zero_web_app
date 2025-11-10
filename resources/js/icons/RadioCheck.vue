<template>
    <div :class="colorClass">
        <svg :width="width" :height="height" :viewBox="'0 0 ' + width + ' ' + height" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.58 20 4 16.42 4 12C4 7.58 7.58 4 12 4C16.42 4 20 7.58 20 12C20 16.42 16.42 20 12 20Z"
                fill="currentColor" />
            <path
                d="M12 17C14.7614 17 17 14.7614 17 12C17 9.23858 14.7614 7 12 7C9.23858 7 7 9.23858 7 12C7 14.7614 9.23858 17 12 17Z"
                fill="currentColor" />
        </svg>
    </div>
</template>

<script setup>

const props = defineProps({
    colorClass: {
        type: String,
        default: 'text-[#e2e8f0]'
    },
    width: {
        type: String,
        default: '24'
    },
    height: {
        type: String,
        default: '24'
    }
});
</script>
