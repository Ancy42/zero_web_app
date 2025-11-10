<template>
    <SectionContainerV2 :title="'Top Categories'" :isLoading="isLoading">
        <template #button>
            <div class="flex justify-center items-center gap-4">
                <router-link
                    to="/categories"
                    class="text-primary text-sm lg:text-lg font-normal leading-normal"
                >
                    {{ $t("View All") }}
                </router-link>
            </div>
        </template>

        <div class="hidden lg:grid grid-cols-3 xl:grid-cols-4 gap-4">
            <CategoryCard
                v-for="category in categories"
                :key="category.id"
                :category="category"
            />
        </div>

        <div class="block lg:hidden" :dir="master.langDirection || 'ltr'">
            <swiper
                :breakpoints="breakpoints"
                :loop="false"
                ref="swiperRef"
                @swiper="onSwiper"
                :modules="modules"
                class="green_bullet"
            >
                <swiper-slide
                    v-if="!isLoading"
                    v-for="category in categories"
                    :key="category.id"
                    class=""
                >
                    <CategoryCard :category="category" />
                </swiper-slide>

                <!-- loading -->
                <swiper-slide v-else v-for="i in 4" :key="i">
                    <SkeletonLoader class="w-full h-[146px] rounded-lg" />
                </swiper-slide>
            </swiper>
        </div>
    </SectionContainerV2>
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
import SectionContainerV2 from "../SectionContainerV2.vue";
import CategoryCard from "../UltraMart/CategoryCard.vue"

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
        slidesPerView: 1.5,
        spaceBetween: 8,
    },
    530: {
        slidesPerView: 3,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 3,
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

<style lang="scss" scoped></style>
