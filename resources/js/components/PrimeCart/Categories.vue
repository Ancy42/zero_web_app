<template>
    <div class="py-4 lg:py-8 px-4 2xl:px-0">
        <SectionContainerV6
            :title="'Featured Category'"
            :isLoading="isLoading"
            :isCenter="true"
        >
            <div :dir="master.langDirection || 'ltr'">
                <swiper
                    :breakpoints="breakpoints"
                    :loop="true"
                    ref="swiperRef"
                    @swiper="onSwiper"
                    :modules="modules"
                    class="categories_swiper_V2 green_bullet_lg"
                    :navigation="{
                        nextEl: '.custom-next',
                        prevEl: '.custom-prev',
                    }"
                    :pagination="{ clickable: true }"
                >
                    <swiper-slide
                        v-if="!isLoading"
                        v-for="category in categories"
                        :key="category.id"
                        class="mb-10 md:mb-16"
                    >
                        <CategoryCard :category="category" />
                    </swiper-slide>

                    <!-- loading -->
                    <swiper-slide v-else v-for="i in 4" :key="i">
                        <SkeletonLoader class="w-full h-[146px] rounded-lg" />
                    </swiper-slide>

                    <!-- custom Navigation buttons -->
                    <div
                        class="custom-prev absolute top-[30%] left-4 z-10 w-8 h-8 hidden lg:flex items-center justify-center rounded-full bg-primary-500 text-white cursor-pointer"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </div>
                    <div
                        class="custom-next absolute top-[30%] right-4 z-10 w-8 h-8 hidden lg:flex items-center justify-center rounded-full bg-primary-500 text-white cursor-pointer"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            class="w-4 h-4"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </div>
                </swiper>
            </div>

            <div v-if="!isLoading" class="flex justify-center items-center">
                <ViewAllBtn3 link="/categories" />
            </div>
        </SectionContainerV6>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import SkeletonLoader from "../SkeletonLoader.vue";
import { useMaster } from "../../stores/MasterStore";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import CategoryCard from "./CategoryCard.vue";
import SectionContainerV6 from "../SectionContainerV6.vue";
import ViewAllBtn3 from "../ViewAllBtn3.vue";

const props = defineProps({
    categories: Array,
    isLoading: {
        type: Boolean,
        default: true,
    },
});

const master = useMaster();

// slider module
const modules = [Navigation, Pagination, A11y, Autoplay];

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
        slidesPerView: 1.5,
        spaceBetween: 8,
    },
    540: {
        slidesPerView: 2.5,
        spaceBetween: 10,
    },
    712: {
        slidesPerView: 3,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 3,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 4,
        spaceBetween: 16,
    },
    1280: {
        slidesPerView: 4.5,
        spaceBetween: 16,
    },
    1440: {
        slidesPerView: 6.2,
        spaceBetween: 16,
    },
};
</script>

<style lang="scss" scoped></style>

<style>
/* categories_swiper_V2 */

.categories_swiper_V2 {
    @apply p-1;
}

.categories_swiper_V2 .swiper-button-prev,
.categories_swiper_V2 .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #3016f3;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.categories_swiper_V2 .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.categories_swiper_V2 .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

[dir="ltr"] .categories_swiper_V2 .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .categories_swiper_V2 .swiper-button-next {
    left: auto;
    right: 20px;
}

.categories_swiper_V2 .swiper-button-prev:after,
.categories_swiper_V2 .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}
</style>
