<script setup>
import { useSlots } from "vue";
import SkeletonLoader from "./SkeletonLoader.vue";

const props = defineProps({
    title: String,
    isLoading: {
        type: Boolean,
        default: true,
    },
    isDark: {
        type: Boolean,
        default: false,
    },
    isCenter: {
        type: Boolean,
        default: false,
    },
});

const slots = useSlots();
</script>

<template>
    <section class="container-3">
        <div v-if="!isLoading" class="mb-4 lg:mb-8">
            <div
                class="flex items-center gap-4 flex-wrap"
                :class="isCenter ? 'justify-center' : 'justify-between'"
            >
                <div
                    class="text-xl lg:text-3xl font-semibold font-['Lato'] lg:font-['Inter'] leading-7 lg:leading-10"
                    :class="isDark ? 'text-white' : 'text-black'"
                >
                    {{ $t(title) }}
                </div>

                <slot name="button"></slot>
            </div>

            <div   v-if="slots.tabs" class="flex justify-center items-center mt-4 lg:mt-8">
                <slot name="tabs"></slot>
            </div>
        </div>

        <!-- loading -->
        <SkeletonLoader
            v-else
            class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg mb-8"
        />

        <slot />
    </section>
</template>
