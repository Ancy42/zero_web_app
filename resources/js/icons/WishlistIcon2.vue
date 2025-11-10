<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        :width="width"
        :height="height"
        :class="colorClass"
        viewBox="0 0 24 24"
        fill="none"
    >
        <path
            d="M20.8301 11.4651C19.6571 17.5151 12.0001 21.0001 12.0001 21.0001C12.0001 21.0001 4.34304 17.5151 3.16905 11.4651C2.44904 7.75308 4.0221 4.01906 8.0211 4.00006C11.0001 3.98606 12.0001 6.98803 12.0001 6.98803C12.0001 6.98803 13.0001 3.98506 15.9781 4.00006C19.9861 4.01906 21.5501 7.75408 20.8301 11.4651Z"
            fill="#17181D"
            :class="colorClass"
        />
    </svg>
</template>

<script setup>
const props = defineProps({
    colorClass: {
        type: String,
        default: "fill-primary",
    },
    width: {
        type: String,
        default: "24",
    },
    height: {
        type: String,
        default: "24",
    },
});
</script>
