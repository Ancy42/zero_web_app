<template>
    <div class="py-4 lg:py-[60px]">
        <SectionContainerV6
            :title="'Top Rated Shops'"
            :isLoading="isLoading"
            :isCenter="true"
        >
            <!-- Shops -->

            <div>
                <swiper
                    :breakpoints="breakpoints"
                    :loop="true"
                    ref="swiperRef"
                    @swiper="onSwiper"
                    :modules="modules"
                    class="shops_swiper"
                >
                    <swiper-slide
                        v-if="!isLoading"
                        v-for="shop in shops"
                        :key="shop.id"
                    >
                        <ShopCardTop :shop="shop" />
                    </swiper-slide>

                    <swiper-slide v-else v-for="i in 4" :key="i">
                        <SkeletonLoader class="w-full h-[100px] sm:h-[100px]" />
                    </swiper-slide>
                </swiper>
            </div>

            <div class="flex justify-center items-center gap-4 mt-8">
                <ViewAllBtn3 link="/shops" />
            </div>
        </SectionContainerV6>
    </div>
</template>

<script setup>
import SkeletonLoader from "../SkeletonLoader.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Navigation, Pagination, A11y, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

import { ref } from "vue";
import { useMaster } from "../../stores/MasterStore";
import ShopCardTop from "../PrimeCart/ShopCardTop.vue";
import SectionContainerV6 from "../SectionContainerV6.vue";
import ViewAllBtn3 from "../ViewAllBtn3.vue";

// store
const master = useMaster();

// slider module
const modules = [Navigation, Pagination, A11y, Autoplay];

const { shops, isLoading } = defineProps(["shops", "isLoading"]);

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
    712: {
        slidesPerView: 2.2,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 2.5,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
    1440: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
};
</script>

<style>
.shops_swiper {
    @apply py-1;
}

.shops_swiper .swiper-wrapper {
}
.shops_swiper .swiper-button-prev,
.shops_swiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
}

.shops_swiper .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.shops_swiper .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}

.shops_swiper .swiper-button-prev:after,
.shops_swiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.shops_swiper .swiper-pagination-bullet {
    background: white;
    opacity: 1;
    @apply md:hidden;
}

.shops_swiper .swiper-pagination-bullet-active {
    @apply bg-primary w-6 h-2 rounded-lg transition-all duration-200;
}
</style>
