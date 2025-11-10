<template>
    <!-- <div
        v-if="themeName == 'MegaMart'"
        class="sticky top-0 md:top-10 glassmorphism z-20 w-full transition-all duration-100 ease-out"
        :class="[
            scrollY > 0
                ? '-translate-y-0 md:-translate-y-10 xl:-translate-y-12 1xl:-translate-y-10'
                : 'translate-y-0',
            route.fullPath == '/' && 'md:fixed',
        ]"
    >
        <component :is="components[themeName]?.middle" />
    </div> -->

    <!-- liquid glass effect  -->
    <div
        v-if="themeName == 'MegaMart'"
        class="sticky top-0 md:top-10 z-20 w-full transition-all duration-100 ease-out"
        :class="[
            scrollY > 0
                ? '-translate-y-0 md:-translate-y-10 xl:-translate-y-12 1xl:-translate-y-10'
                : 'translate-y-0',
            route.fullPath == '/' && 'md:fixed',
        ]"
    >
        <GlassContainer >
            <component :is="components[themeName]?.middle" />
        </GlassContainer>
    </div>

    <div v-else class="sticky top-0 bg-white z-20">
        <component :is="components[themeName]?.middle" />
        <component v-if="showBottomNav" :is="components[themeName]?.bottom" />
    </div>
</template>

<script setup>
import { defineAsyncComponent } from "vue";
import { useScroll } from "../composables/useScroll";
import { useRoute } from "vue-router";
import GlassContainer from "./GlassContainer.vue";

const props = defineProps({
    themeName: {
        type: String,
        default: "",
    },
    showBottomNav: {
        type: Boolean,
        default: true,
    },
});

// Define a mapping of themeName to component paths
const components = {
    NovaStore: {
        middle: defineAsyncComponent(() =>
            import("./NovaStore/NavbarMiddle.vue")
        ),
        bottom: defineAsyncComponent(() =>
            import("./NovaStore/NavbarBottom.vue")
        ),
    },
    NextMart: {
        middle: defineAsyncComponent(() =>
            import("./NextMart/NavbarMiddle.vue")
        ),
    },
    MegaMart: {
        middle: defineAsyncComponent(() =>
            import("./MegaMart/NavbarMiddle.vue")
        ),
    },
    PrimeCart: {
        middle: defineAsyncComponent(() =>
            import("./PrimeCart/NavbarMiddle.vue")
        ),
        bottom: defineAsyncComponent(() =>
            import("./PrimeCart/NavbarBottom.vue")
        ),
    },
    UltraMart: {
        middle: defineAsyncComponent(() =>
            import("./UltraMart/NavbarMiddle.vue")
        ),
        bottom: defineAsyncComponent(() =>
            import("./UltraMart/NavbarBottom.vue")
        ),
    },
};

const {
    scrollY,
    viewportHeight,
    documentHeight,
    scrollPercentage,
    viewportWidth,
} = useScroll();
const route = useRoute();
</script>

<style scoped>
.liquid-glass-element {
    backdrop-filter: blur(10px) brightness(0.8);
    background-color: rgba(
        255,
        255,
        255,
        0.1
    ); /* Semi-transparent background */
    border-radius: 15px; /* Rounded corners for a softer look */
}

/* Container for the background image */

</style>
