<template>
    <div :class="colorClass">
        <svg :width="width" :height="height" :viewBox="'0 0 ' + width + ' ' + height" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M17.4395 14.62L19.9995 12.06L17.4395 9.5" stroke="currentColor" stroke-width="1.5"
                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M9.75977 12.0586H19.9298" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10"
                stroke-linecap="round" stroke-linejoin="round" />
            <path d="M11.7598 20C7.33977 20 3.75977 17 3.75977 12C3.75977 7 7.33977 4 11.7598 4" stroke="currentColor"
                stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
</template>

<script setup>

const props = defineProps({
    colorClass: {
        type: String,
        default: 'text-[#475569]'
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
