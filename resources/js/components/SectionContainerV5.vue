<script setup>
import { useSlots } from "vue";
import SkeletonLoader from "./SkeletonLoader.vue";

const props = defineProps({
    title: String,
    isLoading: {
        type: Boolean,
        default: true,
    },
    isCenter: {
        type: Boolean,
        default: false,
    },
});

const slots = useSlots();
</script>

<template>
    <section class="container-2 py-[60px]">
        <div v-if="!isLoading" class="mb-4 lg:mb-8">
            <div
                class="flex items-center gap-4 flex-wrap"
                :class="isCenter ? 'justify-center' : 'justify-between'"
            >
                <div
                    class="text-seocondary text-xl lg:text-3xl font-semibold leading-7 lg:leading-10"
                >
                    {{ $t(title) }}
                </div>

                <slot name="button"></slot>
            </div>

            <slot name="tabs"></slot>
        </div>

        <!-- loading -->
        <div
            v-else
            class="flex items-center"
            :class="isCenter ? 'justify-center' : 'justify-between'"
        >
            <SkeletonLoader
                class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg mb-8"
            />
        </div>

        <slot />
    </section>
</template>
