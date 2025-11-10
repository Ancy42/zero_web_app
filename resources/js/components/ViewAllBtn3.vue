<template>
    <RouterLink
        :to="link"
        class="w-28 h-10 md:w-32 md:h-12 px-6 py-4 bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-primary-500 inline-flex justify-center items-center gap-4"
    >
        <div
            class="justify-start text-primary-500 text-sm md:text-base font-semibold leading-normal"
        >
            {{ $t("View All") }}
        </div>
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
