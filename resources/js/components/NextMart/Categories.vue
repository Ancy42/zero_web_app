<template>
    <div class="bg-white px-4 2xl:px-0">
        <section class="">
            <SectionContainerV5
                :title="'Feature Category'"
                :isLoading="isLoading"
                :isCenter="true"
            >
                <div
                    v-if="!isLoading"
                    class="grid grid-cols-2 sm2:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2 md:gap-6 "
                >
                    <CategoryCard
                        :isDefault="true"
                    />
                    <CategoryCard
                        v-for="category in categories"
                        :key="category.id"
                        :category="category"
                    />
                </div>

                <div
                    v-else
                    class="grid grid-cols-2 sm2:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2 md:gap-6"
                >
                    <SkeletonLoader
                        v-for="i in 6"
                        :key="i"
                        class="w-full h-[146px] rounded-lg"
                    />
                </div>
            </SectionContainerV5>
        </section>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/20/solid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import SkeletonLoader from "../SkeletonLoader.vue";
import { useMaster } from "../../stores/MasterStore";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import CategoryCard from "./CategoryCard.vue";
import SectionContainerV5 from "../SectionContainerV5.vue";

const props = defineProps({
    categories: Array,
    isLoading: {
        type: Boolean,
        default: true,
    },
});

const master = useMaster();

// slider module
const modules = [Navigation, A11y, Autoplay];

const swiperInstance = ref();

function onSwiper(swiper) {
    swiperInstance.value = swiper;
}

const swiperNextSlide = () => {
    swiperInstance.value.slideNext();
};
const swiperPrevSlide = () => {
    swiperInstance.value.slidePrev();
};

const breakpoints = {
    320: {
        slidesPerView: 2,
        spaceBetween: 8,
    },
    768: {
        slidesPerView: 4,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 3,
        spaceBetween: 16,
    },
    1280: {
        slidesPerView: 4,
        spaceBetween: 16,
    },
};
</script>

<style scoped></style>
