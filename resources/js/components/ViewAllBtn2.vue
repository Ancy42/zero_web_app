<template>
    <RouterLink
        :to="link"
        class=" text-base lg:text-xl font-normal leading-normal lg:leading-7 " :class="textColor ? textColor : 'text-zinc-900'"
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
    textColor:{
        type: String,
        default: 'text-primary'
    }
});
</script>
