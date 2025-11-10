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
    <section class="max-w-[1408px] mx-auto px-4 2xl:px-0 pb-4 xl:pb-16">

        <slot name="counter"></slot>


        <div v-if="!isLoading" class=" my-4 lg:my-8">
            <div class="flex justify-between items-center gap-4 flex-wrap">
                <div
                    class="text-seocondary text-xl lg:text-3xl font-semibold font-['Lato'] lg:font-['Inter'] leading-7 lg:leading-10"
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
