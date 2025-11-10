<script setup>
import SkeletonLoader from "./SkeletonLoader.vue";

const props = defineProps({
    title: String,
    isLoading: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <section class="container-4 py-4 xl:py-16">
        <div v-if="!isLoading" class="mb-4 lg:mb-8">
            <div class="flex justify-between items-center gap-4 flex-wrap">
                <div
                    class="text-seocondary text-lg lg:text-3xl font-semibold font-['Inter'] leading-relaxed lg:leading-10"
                >
                    {{ $t(title) }}
                </div>
                

                <slot name="button"></slot>
            </div>

            <slot name="tabs"></slot>
        </div>

        <!-- loading -->
        <SkeletonLoader
            v-else
            class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg mb-8"
        />

        <slot />
    </section>
</template>
