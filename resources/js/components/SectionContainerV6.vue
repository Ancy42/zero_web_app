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
    <section class="container-2 px-4 2xl:px-0">
        <div v-if="!isLoading" class="mb-4 lg:mb-8">
            <div
                class="flex items-center gap-4 flex-wrap"
                :class="isCenter ? 'justify-center' : 'justify-between'"
            >
                <div
                    class="relative space-y-2"
                    :class="isCenter ? 'text-center' : 'text-start'"
                >
                    <p
                        class="text-xl lg:text-3xl font-semibold leading-7 lg:leading-10 un"
                        :class="isDark ? 'text-white' : 'text-black'"
                    >
                        {{ $t(title) }}
                    </p>

                    <div v-if="slots.tabs">
                        <slot name="tabs"></slot>
                    </div>

                    <div class="flex justify-center">
                        <img
                            v-if="isCenter"
                            src="../../../public/assets/images/homepage/border-line.svg"
                            alt=""
                            class="hidden lg:block"
                        />
                        <img
                            v-if="!isCenter"
                            src="../../../public/assets/images/homepage/border-line-2.svg"
                            alt=""
                            class="hidden lg:block"
                        />
                    </div>
                </div>

                <slot name="button"></slot>
            </div>
        </div>

        <!-- loading -->
        <div
            class="flex items-center"
            :class="isCenter ? 'justify-center' : 'justify-start'"
            v-else
        >
            <SkeletonLoader
                class="w-48 sm:w-60 md:w-72 lg:w-96 h-12 rounded-lg mb-8"
            />
        </div>

        <slot />
    </section>
</template>
