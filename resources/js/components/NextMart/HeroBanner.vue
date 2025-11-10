<template>
    <div
        v-if="!isLoading"
        class="min-h-[200px] md:min-h-[400px] lg:min-h-[633px] flex items-center overflow-hidden relative"
        :dir="master.langDirection || 'ltr'"
    >
        <div
            class="container-5 w-full h-full flex flex-col-reverse gap-4 lg:gap-0 lg:flex-row items-center justify-between"
            :class="master.langDirection == 'ltr' ? '' : 'flex-row-reverse'"
        >
            <div
                class="h-full w-full max-w-[500px] 2xl:max-w-[608px] flex flex-col justify-center items-start gap-8 z-10 relative"
            >
                <div class="text-center lg:text-start 2xl:space-y-4">
                    <p
                        class="text-primary-500 text-2xl md:text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight md:leading-normal lg:leading-[80px]"
                    >
                        {{ $t("The Grocery") }}
                    </p>
                    <p
                        class="text-zinc-900 text-2xl md:text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight md:leading-normal lg:leading-[80px]"
                    >
                        {{ $t("World in One Cart") }}
                        
                    </p>

                    <div
                        class="text-zinc-900 text-sm md:text-lg font-normal leading-normal md:leading-relaxed"
                    >
                        {{ $t("Enjoy the freedom to browse and buy your favorite products wherever you are — day or night, on any device.") }}
                    </div>
                </div>

                <form
                    @submit.prevent="goToSearch"
                    class="w-full h-10 md:h-14  bg-white rounded-lg outline outline-1 outline-offset-[-1px] outline-white/25 flex justify-between items-center gap-2 overflow-hidden"
                >
                    <input
                        type="text"
                        class="w-full h-full outline-none placeholder:text-gray-400 text-sm md:text-base font-normal leading-normal px-4"
                        :placeholder="$t('Search Product Hear...')"
                    />
                    <button
                        class="h-full px-2 md:px-4 bg-primary-500 rounded-lg min-w-20 md:min-w-28 text-white text-sm md:text-base font-normal leading-normal"
                    >
                        {{ $t("Search") }}
                    </button>
                </form>

                <div class="w-full">
                    <CounterContainer />
                </div>
            </div>

            <div
                class="w-full h-full flex justify-center lg:justify-end items-end z-10 relative"
            >
                <img
                    src="../../../../public/assets/images/homepage/hero-banner3.png"
                    alt=""
                    class="max-w-[300px] lg:max-w-[450px] xl:max-w-[658px]"
                />
            </div>

            <div
                class="w-[150px] h-[1500px] -left-20 top-0 absolute origin-top-left -rotate-45 bg-red-500 blur-[300px] z-0"
            ></div>
        </div>
    </div>

    <!-- Skeleton loader -->
    <div v-else class="min-h-[633px]">
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
import CounterContainer from "../NextMart/CounterContainer.vue"
import { useRouter } from "vue-router";

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
const router = useRouter();

const goToSearch = (e) => {
    e.preventDefault(); // Prevent default just in case
    const formData = new FormData(e.target);
    const searchValue = formData.get("search");
    console.log(searchValue); // The typed search text
    master.search = searchValue;
    router.push({ name: "products" });
};
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
