<template>
    <div
        v-if="!isLoading"
        class="main-container mt-2  lg:mt-[24px] lg:mb-[32px]"
        :dir="master.langDirection || 'ltr'"
    >
        <div class="col-span-4 lg:col-span-3">
            <!-- Main Banner Slider -->
            <swiper
                :navigation="true"
                :pagination="{ clickable: true }"
                :slides-per-view="1"
                :space-between="20"
                :modules="modules"
                class="novastore_slider rounded-lg"
                effect="fade"
                :loop="false"
                :autoplay="{
                    delay: 4000,
                    disableOnInteraction: false,
                }"
            >
                <swiper-slide v-for="banner in banners" :key="banner.id" class="mb-2 lg:mb-0">
                    <img
                        :src="banner.thumbnail"
                        loading="lazy"
                        class="w-full rounded-lg object-cover aspect-[10/4]"
                    />
                </swiper-slide>
            </swiper>
        </div>
    </div>

    <!-- Skeleton loader -->
    <div v-else class="main-container mt-2  lg:mt-[24px] lg:mb-[32px]">
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
const master = useMaster();
import SkeletonLoader from "../SkeletonLoader.vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/effect-fade";

import "swiper/css/navigation";
import "swiper/css/pagination";

const modules = [Navigation, Pagination, A11y, Autoplay, EffectFade];

const props = defineProps({
    banners: Array,
    ads: Array,
    isLoading: {
        type: Boolean,
        default: true,
    },
});
</script>

<style>
.novastore_slider .swiper-button-prev,
.novastore_slider .swiper-button-next {
    position: absolute;
    width: 40px;
    height: 40px;
    background-color: #fff;
    color: #475569 !important;
    border-radius: 8px !important;
    margin-top: auto;
    @apply hidden md:flex;
}

.novastore_slider .swiper-button-next {
    left: auto;
    right: 20px;
    bottom: 20px;
}

.novastore_slider .swiper-button-prev {
    left: auto;
    right: 70px;
    bottom: 20px;
}
[dir="ltr"] .novastore_slider .swiper-button-prev {
    left: auto;
    right: 70px;
}

[dir="ltr"] .novastore_slider .swiper-button-next {
    left: auto;
    right: 20px;
}

/* RTL */
[dir="rtl"] .novastore_slider .swiper-button-prev {
    right: auto;
    left: 70px;
}

[dir="rtl"] .novastore_slider .swiper-button-next {
    right: auto;
    left: 20px;
}

.novastore_slider .swiper-button-prev:after,
.novastore_slider .swiper-button-next:after {
    color: black;
    font-size: 12px !important;
    font-weight: bolder;
}

.novastore_slider .swiper-pagination-bullet {
    background: white;
    opacity: 1;
    @apply h-2 w-2 md:w-4 md:h-4 rounded-lg transition-all duration-200;
}

.novastore_slider .swiper-pagination-bullet-active {
    @apply bg-primary w-6 md:h-4 rounded-lg transition-all duration-200;
}
</style>
