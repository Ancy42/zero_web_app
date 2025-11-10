<script setup>
import { RiHomeLine, RiArrowUpDoubleLine } from "vue-remix-icons";
import { useScroll } from "../composables/useScroll";

const {
    scrollY,
    viewportHeight,
    documentHeight,
    scrollPercentage,
    viewportWidth,
} = useScroll();
</script>

<template>
    <a
        href="#"
        class="fixed  right-[15px] bg-primary h-[50px] w-[50px] rounded-full flex justify-center items-center transition-all duration-500 hover:bg-black z-10"
        :class="scrollY > 400 ? 'bottom-4' : '-bottom-14'"
    >
        <RiArrowUpDoubleLine
            class="w-[25px] h-[25px] text-white animate-bounce duration-700"
        />
    </a>
</template>
