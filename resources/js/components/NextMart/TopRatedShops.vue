<template>
    <div class="bg-white px-4 2xl:px-0">
        <SectionContainerV5 :title="'Top Rated Shops'" :isLoading="isLoading">
            <template #button>
                <div class="flex justify-center items-center gap-4">
                    <ViewAllBtn2 link="/shops" />
                </div>
            </template>
            <!-- Shops -->
            <div :dir="master.langDirection || 'ltr'" class="">
                <swiper
                    :breakpoints="breakpoints"
                    :loop="true"
                    ref="swiperRef"
                    @swiper="onSwiper"
                    :modules="modules"
                    class="shops_swiper"
                    :autoplay="{
                        delay: 4000,
                        disableOnInteraction: false,
                    }"
                >
                    <swiper-slide
                        v-if="!isLoading"
                        v-for="shop in shops"
                        :key="shop.id"
                    >
                        <ShopCardTop :shop="shop" />
                    </swiper-slide>

                    <!-- loading -->
                    <swiper-slide v-else v-for="i in 3" :key="i">
                        <SkeletonLoader class="w-full h-[200px] sm:h-[250px]" />
                    </swiper-slide>
                </swiper>
            </div>

            <!-- banner parts  -->
            <div
                v-if="
                    master.offerBanners.length >= 4 ||
                    master.offerBanners.length >= 5
                "
                class="grid grid-cols-2 gap-2 lg:gap-8 grid-rows-[80px] md:grid-rows-[200px] lg:grid-rows-[288px] mt-8"
            >
                <img
                    v-if="master.offerBanners.length >= 4"
                    :src="master.offerBanners[4]?.offer_banner"
                    alt=""
                    class="rounded-lg lg:rounded-2xl w-full h-full"
                />
                <img
                    v-if="master.offerBanners.length >= 5"
                    :src="master.offerBanners[5]?.offer_banner"
                    alt=""
                    class="rounded-lg lg:rounded-2xl w-full h-full"
                />
            </div>
        </SectionContainerV5>
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
import ShopCardTop from "./ShopCardTop.vue";
import ViewAllBtn2 from "../ViewAllBtn2.vue";
import SectionContainerV5 from "../SectionContainerV5.vue";

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
        slidesPerView: 1.5,
        spaceBetween: 10,
    },
    712: {
        slidesPerView: 2.5,
        spaceBetween: 10,
    },
    768: {
        slidesPerView: 3,
        spaceBetween: 10,
    },
    1024: {
        slidesPerView: 4,
        spaceBetween: 24,
    },
    1440: {
        slidesPerView: 5.5,
        spaceBetween: 24,
    },
};
</script>

<style>
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
