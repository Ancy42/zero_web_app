<template>
    <div
        class="bg-[url('https://i.ibb.co.com/Fqx8F3fB/featured-category-bg.png')] relative bg-no-repeat bg-cover bg-center overflow-hidden py-7"
    >
        <div class="relative z-10">
            <SectionContainerV4
                :title="'Feature Category'"
                :isLoading="isLoading"
                :isDark="true"
            >
                <template #button>
                    <div class="flex justify-center items-center gap-4">
                        <router-link
                            to="/categories"
                            class="text-primary text-base md:text-xl font-['Lato'] font-normal leading-7"
                        >
                            {{ $t("View All") }}
                        </router-link>

                        <div
                            class="hidden gap-3"
                            :class="
                                master.langDirection == 'rtl'
                                    ? 'md:flex justify-center items-center flex-row-reverse'
                                    : 'md:flex justify-center items-center'
                            "
                        >
                            <button
                                class="w-8 h-8 rounded-lg justify-center items-center gap-2 flex bg-white/20 transition-all duration-200 hover:bg-primary group"
                                @click="swiperPrevSlide"
                            >
                                <ChevronLeftIcon
                                    class="w-6 h-6 text-white group-hover:text-white"
                                />
                            </button>
                            <button
                                class="w-8 h-8 rounded-lg justify-center items-center gap-2 flex bg-white/20 transition-all duration-200 hover:bg-primary group"
                                @click="swiperNextSlide"
                            >
                                <ChevronRightIcon
                                    class="w-6 h-6 text-white group-hover:text-white"
                                />
                            </button>
                        </div>
                    </div>
                </template>

                <div :dir="master.langDirection || 'ltr'">
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
                            class="mb-8"
                        >
                            <CategoryCard :category="category" />
                        </swiper-slide>

                        <!-- loading -->
                        <swiper-slide v-else v-for="i in 8" :key="i">
                            <SkeletonLoader
                                class="w-full h-[146px] rounded-lg"
                            />
                        </swiper-slide>
                    </swiper>
                </div>
            </SectionContainerV4>
        </div>

        <div
            class="w-96 h-96 -left-28 top-0 absolute bg-green-500 rounded-full blur-[300px]"
        ></div>
        <div
            class="w-96 h-96 -right-28 top-0 absolute bg-green-500 rounded-full blur-[300px]"
        ></div>
    </div>

    <div class="container-2 py-4 md:py-10 lg:py-16">
        <img
            :src="master.offerBanners[0]?.offer_banner"
            alt=""
            class="w-full h-auto max-h-[400px]"
        />
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
import SectionContainer from "../SectionContainer.vue";
import CategoryCard from "../MegaMart/CategoryCard.vue";
import SectionContainerV4 from "../SectionContainerV4.vue";

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
        slidesPerView: 1.2,
        spaceBetween: 10,
    },
    530: {
        slidesPerView: 2.2,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 3.1,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 5,
        spaceBetween: 20,
    },
    1440: {
        slidesPerView: 6,
        spaceBetween: 20,
    },
};
</script>

<style lang="scss" scoped></style>
