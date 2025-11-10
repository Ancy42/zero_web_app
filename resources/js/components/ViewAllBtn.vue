<template>
    <RouterLink
        :to="link"
        class="text-primary text-base lg:text-xl font-normal font-['Lato'] leading-normal lg:leading-7"
    >
        {{ $t("View All") }}
    </RouterLink>
</template>
<script setup>
const props = defineProps({
    link: {
        type: String,
        required: true,
    },
});
</script>
