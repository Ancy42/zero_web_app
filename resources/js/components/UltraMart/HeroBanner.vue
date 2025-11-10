<template>
    <div
        v-if="!isLoading"
        class="container-4 mt-2 lg:mt-[24px] lg:mb-[32px] space-y-1 md:space-y-4"
        :dir="master.langDirection || 'ltr'"
    >
        <div class="">
            <!-- Main Banner Slider -->
            <swiper
                :navigation="true"
                :pagination="{ clickable: true }"
                :slides-per-view="1"
                :space-between="20"
                :modules="modules"
                class="mySwiper rounded-lg"
                effect="fade"
                :loop="false"
                :autoplay="{
                    delay: 4000,
                    disableOnInteraction: false,
                }"
            >
                <swiper-slide
                    v-for="banner in banners"
                    :key="banner.id"
                    class="mb-2 lg:mb-0 aspect-[10/3.5]"
                >
                    <img
                        :src="banner.thumbnail"
                        loading="lazy"
                        class="w-full rounded-lg md:rounded-2xl object-cover"
                    />
                </swiper-slide>
            </swiper>
        </div>

        <div
            v-if="master.offerBanners.length > 0"
            class="grid grid-cols-3 gap-1 md:gap-4 grid-rows-[50px] sm2:grid-rows-[100px] lg:grid-rows-[200px]"
        >
            <a
                v-if="master.offerBanners.length > 0"
                :href="master.offerBanners[0].link"
                target="_blank"
            >
                <img
                    :src="master.offerBanners[0].offer_banner"
                    alt=""
                    class="w-full h-full object-cover rounded-lg md:rounded-2xl"
                />
            </a>
            <a
                v-if="master.offerBanners.length > 1"
                :href="master.offerBanners[1].link"
                target="_blank"
            >
                <img
                    :src="master.offerBanners[1].offer_banner"
                    alt=""
                    class="w-full h-full object-cover rounded-lg md:rounded-2xl"
                />
            </a>
            <a
                v-if="master.offerBanners.length > 2"
                class=""
                :href="master.offerBanners[2].link"
                target="_blank"
            >
                <img
                    :src="master.offerBanners[2].offer_banner"
                    alt=""
                    class="w-full h-full object-cover rounded-lg md:rounded-2xl"
                />
            </a>
        </div>
    </div>

    <!-- Skeleton loader -->
    <div v-else class="container-2 mt-2 lg:mt-[24px] lg:mb-[32px]">
        <div class="col-span-4 lg:col-span-3">
            <div class="w-full aspect-[9/4] object-cover rounded-lg">
                <SkeletonLoader class="w-full h-full object-cover rounded-lg" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import {
    Navigation,
    Pagination,
    A11y,
    Autoplay,
    EffectFade,
} from "swiper/modules";
import { useMaster } from "../../stores/MasterStore";
import SkeletonLoader from "../SkeletonLoader.vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/effect-fade";

import "swiper/css/navigation";
import "swiper/css/pagination";

const props = defineProps({
    banners: Array,
    ads: Array,
    isLoading: {
        type: Boolean,
        default: true,
    },
});

const modules = [Navigation, Pagination, A11y, Autoplay, EffectFade];

const master = useMaster();
</script>

<style>
.mySwiper .swiper-button-prev,
.mySwiper .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
    @apply hidden md:flex;
}

.mySwiper .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.mySwiper .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}
[dir="ltr"] .mySwiper .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .mySwiper .swiper-button-next {
    left: auto;
    right: 20px;
}

/* RTL */
[dir="rtl"] .mySwiper .swiper-button-prev {
    right: auto;
    left: 70px;
}

[dir="rtl"] .mySwiper .swiper-button-next {
    right: auto;
    left: 20px;
}

.mySwiper .swiper-button-prev:after,
.mySwiper .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.mySwiper .swiper-pagination-bullet {
    opacity: 1;
    @apply h-2 w-2 bg-slate-400 rounded-lg transition-all duration-200;
}

.mySwiper .swiper-pagination-bullet-active {
    @apply bg-white w-6 h-2 rounded-lg transition-all duration-200;
}
</style>
